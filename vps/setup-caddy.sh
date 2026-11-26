#!/usr/bin/env bash
# Configura Caddy (no Nginx) para justcamm.online en el VPS.
# Ejecutar en el servidor: sudo bash setup-caddy.sh
set -euo pipefail

DOMAIN="portafolio.justcamm.online"
WP_PATH="/var/www/justcamm"
PHP_SOCK="/run/php/php8.4-fpm.sock"
CADDYFILE_SRC="$(cd "$(dirname "$0")" && pwd)/Caddyfile"

echo "==> 1/5 Deteniendo Nginx (si existe)..."
if systemctl is-active --quiet nginx 2>/dev/null; then
  systemctl stop nginx
  systemctl disable nginx
  echo "    Nginx detenido y deshabilitado."
elif command -v nginx >/dev/null 2>&1; then
  systemctl disable nginx 2>/dev/null || true
  echo "    Nginx ya estaba parado."
else
  echo "    Nginx no instalado."
fi

echo "==> 2/5 Instalando Caddy..."
if ! command -v caddy >/dev/null 2>&1; then
  apt-get update -y
  apt-get install -y debian-keyring debian-archive-keyring apt-transport-https curl
  curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' \
    | gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
  curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' \
    | tee /etc/apt/sources.list.d/caddy-stable.list
  apt-get update -y
  apt-get install -y caddy
else
  echo "    Caddy ya instalado: $(caddy version)"
fi

echo "==> 3/5 Preparando docroot $WP_PATH ..."
mkdir -p "$WP_PATH"
# Si venía de portafolio-maca, migrar
if [[ -d /var/www/portafolio-maca && ! -e "$WP_PATH/wp-config.php" ]]; then
  if [[ -e /var/www/portafolio-maca/wp-config.php ]]; then
    echo "    Copiando WordPress desde /var/www/portafolio-maca ..."
    rsync -a /var/www/portafolio-maca/ "$WP_PATH/"
  fi
fi
chown -R www-data:www-data "$WP_PATH"

# Detectar socket PHP-FPM disponible
if [[ ! -S "$PHP_SOCK" ]]; then
  for s in /run/php/php*-fpm.sock; do
    if [[ -S "$s" ]]; then
      PHP_SOCK="$s"
      break
    fi
  done
fi
echo "    Usando PHP-FPM: $PHP_SOCK"

echo "==> 4/5 Instalando Caddyfile..."
cp "$CADDYFILE_SRC" /etc/caddy/Caddyfile
# Caddy espera unix//ruta (doble slash)
sed -i "s|unix//run/php/php[0-9.]*-fpm.sock|unix/${PHP_SOCK}|g" /etc/caddy/Caddyfile
caddy validate --config /etc/caddy/Caddyfile
systemctl enable caddy
systemctl restart caddy

echo "==> 5/5 Listo"
echo "    Dominio: https://$DOMAIN"
echo "    Docroot: $WP_PATH"
echo "    DNS A de $DOMAIN y www → IP de este VPS"
echo "    Caddy obtiene el certificado SSL solo."
systemctl --no-pager status caddy | head -12
