#!/usr/bin/env bash
# Solo sube el tema nasly (contenido + fotos embebidos). No toca la DB ni Nginx/PM2.
# En el VPS:
#   git -C ~/Portafolio-Maca pull
#   sudo bash ~/Portafolio-Maca/vps/deploy-theme-only.sh
set -euo pipefail

REPO="${HOME}/Portafolio-Maca"
WP_PATH="/var/www/justcamm"
THEME_SRC="$REPO/app/public/wp-content/themes/nasly"
THEME_DST="$WP_PATH/wp-content/themes/nasly"

if [[ ! -d "$THEME_SRC" ]]; then
  echo "No encuentro el repo en $REPO"
  echo "Ejecuta: git clone https://github.com/GustavoUNAL/Portafolio-Maca.git ~/Portafolio-Maca"
  exit 1
fi

git -C "$REPO" fetch origin
git -C "$REPO" reset --hard origin/main

sudo mkdir -p "$THEME_DST"
sudo rsync -a --delete "$THEME_SRC/" "$THEME_DST/"
sudo chown -R www-data:www-data "$THEME_DST"

if command -v wp >/dev/null 2>&1 && [[ -f "$WP_PATH/wp-config.php" ]]; then
  cd "$WP_PATH"
  sudo -u www-data wp theme activate nasly || true
  sudo -u www-data wp option update show_on_front posts || true
  sudo -u www-data wp rewrite structure '/%postname%/' --hard || true
  # Quita plantillas FSE en DB que muestran "Hello world"
  sudo -u www-data wp post list --post_type=wp_template --format=ids 2>/dev/null | xargs -r -n1 sudo -u www-data wp post delete --force 2>/dev/null || true
  # Registra las 6 entradas bajo justcamm777 (diseño MACA idéntico)
  # Opcional: JUSTCAMM_PASS='tuclave' sudo bash deploy-theme-only.sh
  PASS="${JUSTCAMM_PASS:-}"
  sudo -u www-data wp option delete maca_journal_seeded 2>/dev/null || true
  if [[ -n "$PASS" ]]; then
    sudo -u www-data wp maca seed --password="$PASS" \
      || sudo -u www-data wp eval "print_r(maca_seed_journal('$PASS'));"
  else
    sudo -u www-data wp maca seed \
      || sudo -u www-data wp eval 'print_r(maca_seed_journal());'
  fi
  sudo -u www-data wp rewrite flush || true
  sudo -u www-data wp cache flush || true
  sudo -u www-data wp option delete maca_rewrite_ver 2>/dev/null || true
fi

echo "OK — tema + posts del Visual Journal en el admin"
echo "Abre https://portafolio.justcamm.online  y  /wp-admin/edit.php"
