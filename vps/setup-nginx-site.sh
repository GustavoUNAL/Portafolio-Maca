#!/usr/bin/env bash
# Añade SOLO el vhost de portafolio.justcamm.online.
# NO detiene Nginx (PM2 y otros sitios siguen activos).
# Ejecutar: sudo bash setup-nginx-site.sh
set -euo pipefail

DOMAIN="portafolio.justcamm.online"
WP_PATH="/var/www/justcamm"
PHP_SOCK="/run/php/php8.4-fpm.sock"
CONF_SRC="$(cd "$(dirname "$0")" && pwd)/nginx-portafolio.conf"
CONF_DST="/etc/nginx/sites-available/portafolio-justcamm"

echo "==> 1/4 Nginx se mantiene activo (no se apaga)..."
if ! systemctl is-active --quiet nginx; then
  echo "    Nginx no está corriendo; lo inicio (sin deshabilitar nada más)."
  systemctl start nginx
else
  echo "    Nginx OK — PM2 / otros sitios intactos."
fi

echo "==> 2/4 Docroot $WP_PATH ..."
mkdir -p "$WP_PATH"
if [[ -d /var/www/portafolio-maca && -f /var/www/portafolio-maca/wp-config.php && ! -f "$WP_PATH/wp-config.php" ]]; then
  echo "    Copiando WordPress desde /var/www/portafolio-maca ..."
  rsync -a /var/www/portafolio-maca/ "$WP_PATH/"
fi
chown -R www-data:www-data "$WP_PATH"

if [[ ! -S "$PHP_SOCK" ]]; then
  for s in /run/php/php*-fpm.sock; do
    if [[ -S "$s" ]]; then
      PHP_SOCK="$s"
      break
    fi
  done
fi
echo "    PHP-FPM: $PHP_SOCK"

echo "==> 3/4 Instalando vhost (solo este dominio)..."
cp "$CONF_SRC" "$CONF_DST"
sed -i "s|unix:/run/php/php[0-9.]*-fpm.sock|unix:${PHP_SOCK}|g" "$CONF_DST"
ln -sfn "$CONF_DST" /etc/nginx/sites-enabled/portafolio-justcamm
nginx -t
systemctl reload nginx

echo "==> 4/4 SSL (si certbot está disponible)..."
if command -v certbot >/dev/null 2>&1; then
  certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos --register-unsafely-without-email --redirect || \
    echo "    Certbot no pudo emitir aún (DNS o rate limit). HTTP sigue activo."
else
  echo "    Instala certbot cuando quieras: sudo apt install certbot python3-certbot-nginx"
  echo "    Luego: sudo certbot --nginx -d $DOMAIN"
fi

echo ""
echo "Listo. Nginx NO se apagó."
echo "Sitio: https://$DOMAIN  →  $WP_PATH"
echo "Otros vhosts / PM2: sin cambios."
