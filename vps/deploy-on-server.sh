#!/usr/bin/env bash
# Ejecutar EN el VPS (como ubuntu, con sudo):
#   git clone ... && sudo bash Portafolio-Maca/vps/deploy-on-server.sh
# NO apaga Nginx (PM2 sigue corriendo).
set -euo pipefail

REPO_URL="https://github.com/GustavoUNAL/Portafolio-Maca.git"
WP_PATH="/var/www/justcamm"
LIVE_URL="https://portafolio.justcamm.online"
LOCAL_URL="http://portafolio.local"
WORKDIR="${HOME}/Portafolio-Maca"

echo "==> 1/5 Clonando / actualizando repo..."
if [[ -d "$WORKDIR/.git" ]]; then
  git -C "$WORKDIR" fetch origin
  git -C "$WORKDIR" reset --hard origin/main
else
  rm -rf "$WORKDIR"
  git clone "$REPO_URL" "$WORKDIR"
fi

echo "==> 2/5 Vhost Nginx (sin apagar el servicio)..."
sudo bash "$WORKDIR/vps/setup-nginx-site.sh"

echo "==> 3/5 WordPress en $WP_PATH ..."
sudo mkdir -p "$WP_PATH"
if [[ ! -f "$WP_PATH/wp-config.php" ]]; then
  if [[ -f /var/www/portafolio-maca/wp-config.php ]]; then
    echo "    Migrando desde portafolio-maca..."
    sudo rsync -a /var/www/portafolio-maca/ "$WP_PATH/"
  else
    echo "ERROR: No hay WordPress en $WP_PATH ni en /var/www/portafolio-maca."
    echo "Instala WordPress ahí primero (wp core download + wp config create)."
    exit 1
  fi
fi

echo "==> 4/5 Tema + uploads desde el repo..."
sudo rsync -a --delete \
  "$WORKDIR/app/public/wp-content/themes/nasly/" \
  "$WP_PATH/wp-content/themes/nasly/"
sudo mkdir -p "$WP_PATH/wp-content/uploads"
sudo rsync -a \
  "$WORKDIR/app/public/wp-content/uploads/" \
  "$WP_PATH/wp-content/uploads/"
sudo chown -R www-data:www-data "$WP_PATH/wp-content/themes/nasly" "$WP_PATH/wp-content/uploads"

echo "==> 5/5 Activar tema + URLs..."
if ! command -v wp >/dev/null 2>&1; then
  curl -sS -o /tmp/wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  sudo mv /tmp/wp-cli.phar /usr/local/bin/wp
  sudo chmod +x /usr/local/bin/wp
fi

cd "$WP_PATH"
sudo -u www-data wp theme activate nasly || true
sudo -u www-data wp option update home "$LIVE_URL" || true
sudo -u www-data wp option update siteurl "$LIVE_URL" || true
sudo -u www-data wp search-replace "$LOCAL_URL" "$LIVE_URL" --all-tables --skip-columns=guid 2>/dev/null || true
sudo -u www-data wp rewrite flush
sudo -u www-data wp cache flush || true

echo ""
echo "Listo → $LIVE_URL"
echo "Nginx / PM2: sin tocar."
echo "DB completa desde tu Mac: ./deploy.sh"
