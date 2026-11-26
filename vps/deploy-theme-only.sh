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
  sudo -u www-data wp rewrite flush || true
  sudo -u www-data wp cache flush || true
fi

echo "OK — tema estático con fotos en $THEME_DST"
echo "Abre https://portafolio.justcamm.online"
