#!/usr/bin/env bash
# Despliegue rápido MACA: Local → VPS
# Uso: ./deploy.sh

set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

if [[ ! -f "$ROOT/deploy.env" ]]; then
  echo "Falta deploy.env"
  echo "  cp deploy.env.example deploy.env"
  exit 1
fi

# shellcheck disable=SC1091
source "$ROOT/deploy.env"

: "${VPS_HOST:?}"
: "${VPS_WP_PATH:?}"
: "${LIVE_URL:?}"
: "${LOCAL_URL:?}"
VPS_PORT="${VPS_PORT:-22}"

PHP_BIN="${PHP_BIN:-$HOME/Library/Application Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php}"
MYSQL_BIN_DIR="${MYSQL_BIN_DIR:-$HOME/Library/Application Support/Local/lightning-services/mysql-8.4.0+2/bin/darwin-arm64/bin}"
SITE_ID="${SITE_ID:-xpUvslERz}"
SOCK="${MYSQL_SOCK:-$HOME/Library/Application Support/Local/run/$SITE_ID/mysql/mysqld.sock}"
PUBLIC="$ROOT/app/public"
WP_CLI="${WP_CLI:-$PUBLIC/wp-cli.phar}"
STAMP="$(date +%Y%m%d-%H%M%S)"
OUT="$ROOT/.deploy/$STAMP"
TMP_INI="$ROOT/.deploy/php.ini"

mkdir -p "$OUT" "$ROOT/.deploy"
export PATH="$MYSQL_BIN_DIR:/usr/bin:/bin:/usr/sbin:/sbin:$PATH"

cat > "$TMP_INI" <<EOF
mysqli.default_socket="$SOCK"
pdo_mysql.default_socket="$SOCK"
EOF

if [[ ! -f "$PHP_BIN" ]]; then
  echo "No encuentro PHP de Local: $PHP_BIN"
  exit 1
fi

if [[ ! -f "$WP_CLI" ]]; then
  echo "Descargando WP-CLI..."
  curl -sS -o "$WP_CLI" https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
fi

wp() {
  (cd "$PUBLIC" && "$PHP_BIN" -c "$TMP_INI" "$WP_CLI" "$@")
}

echo "==> 1/4 Exportando base de datos..."
# Prefer mysqldump with Local socket (más fiable que wp db export aquí)
if [[ -x "$MYSQL_BIN_DIR/mysqldump" ]]; then
  "$MYSQL_BIN_DIR/mysqldump" --socket="$SOCK" -u root -proot local --add-drop-table 2>/dev/null \
    | grep -v "Using a password" > "$OUT/maca.sql"
else
  wp db export "$OUT/maca.sql" --add-drop-table
fi
if [[ ! -s "$OUT/maca.sql" ]]; then
  echo "Error: SQL vacío"
  exit 1
fi
echo "    SQL: $(du -h "$OUT/maca.sql" | awk '{print $1}')"

echo "==> 2/4 Empaquetando tema + uploads..."
mkdir -p "$OUT/wp-content/themes"
cp -a "$PUBLIC/wp-content/themes/nasly" "$OUT/wp-content/themes/"
cp -a "$PUBLIC/wp-content/uploads" "$OUT/wp-content/"

echo "==> 3/4 Subiendo a $VPS_HOST ..."
SSH=(ssh -p "$VPS_PORT" -t "$VPS_HOST")
RSYNC=(rsync -az -e "ssh -p $VPS_PORT")

# Staging en home del usuario (sin permisos de www) y luego sudo al destino
REMOTE_STAGING="\$HOME/.maca-deploy-staging"
"${SSH[@]}" "mkdir -p \$HOME/.maca-deploy-staging/themes \$HOME/.maca-deploy-staging/uploads \$HOME/.maca-deploy-staging/sql"

"${RSYNC[@]}" --delete \
  "$OUT/wp-content/themes/nasly/" \
  "$VPS_HOST:.maca-deploy-staging/themes/nasly/"

"${RSYNC[@]}" --delete \
  "$OUT/wp-content/uploads/" \
  "$VPS_HOST:.maca-deploy-staging/uploads/"

scp -P "$VPS_PORT" "$OUT/maca.sql" "$VPS_HOST:.maca-deploy-staging/sql/maca.sql"

echo "==> 4/4 Instalando en WordPress del VPS..."
ssh -p "$VPS_PORT" "$VPS_HOST" bash -s <<EOF
set -euo pipefail
ST="\$HOME/.maca-deploy-staging"
WP='$VPS_WP_PATH'

sudo mkdir -p "\$WP/wp-content/themes" "\$WP/wp-content/uploads" "\$WP/.maca-deploy"
sudo rsync -a --delete "\$ST/themes/nasly/" "\$WP/wp-content/themes/nasly/"
sudo rsync -a "\$ST/uploads/" "\$WP/wp-content/uploads/"
sudo cp "\$ST/sql/maca.sql" "\$WP/.maca-deploy/maca.sql"
sudo chown -R www-data:www-data "\$WP/wp-content/themes/nasly" "\$WP/wp-content/uploads"

# WP-CLI si falta
if ! command -v wp >/dev/null 2>&1; then
  curl -sS -o /tmp/wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  sudo mv /tmp/wp-cli.phar /usr/local/bin/wp
  sudo chmod +x /usr/local/bin/wp
fi

cd "\$WP"
sudo -u www-data wp db import .maca-deploy/maca.sql
sudo -u www-data wp search-replace '$LOCAL_URL' '$LIVE_URL' --all-tables
sudo -u www-data wp theme activate nasly || true
sudo -u www-data wp rewrite flush
sudo -u www-data wp cache flush || true
echo "OK → $LIVE_URL"
EOF

echo ""
echo "Listo. Abre: $LIVE_URL"
