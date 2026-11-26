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

echo "==> 2/4 Vhost Nginx (sin apagar el servicio)..."
sudo bash "$WORKDIR/vps/setup-nginx-site.sh"

echo "==> 3/4 WordPress en $WP_PATH ..."
sudo mkdir -p "$WP_PATH"
if [[ ! -f "$WP_PATH/wp-config.php" ]]; then
  if [[ -f /var/www/portafolio-maca/wp-config.php ]]; then
    echo "    Migrando desde portafolio-maca..."
    sudo rsync -a /var/www/portafolio-maca/ "$WP_PATH/"
  else
    echo "ERROR: No hay WordPress en $WP_PATH ni en /var/www/portafolio-maca."
    exit 1
  fi
fi

echo "==> 4/4 Tema estático (fotos + textos en el código)..."
sudo bash "$WORKDIR/vps/deploy-theme-only.sh"

# Asegura URLs del sitio
if command -v wp >/dev/null 2>&1; then
  cd "$WP_PATH"
  sudo -u www-data wp option update home "$LIVE_URL" || true
  sudo -u www-data wp option update siteurl "$LIVE_URL" || true
fi

echo ""
echo "Listo → $LIVE_URL"
echo "Contenido embebido en el tema — no hace falta importar la DB."
echo "Nginx / PM2: sin tocar."
