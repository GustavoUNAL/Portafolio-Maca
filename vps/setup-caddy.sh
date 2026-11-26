#!/usr/bin/env bash
# OBSOLETO para este VPS.
# NO uses este script: apagaría Caddy en conflicto con Nginx / PM2.
# Usa en su lugar: sudo bash setup-nginx-site.sh
set -euo pipefail
echo "ABORTADO: este VPS usa Nginx (PM2 y otros sitios)."
echo "Ejecuta: sudo bash $(dirname "$0")/setup-nginx-site.sh"
exit 1
