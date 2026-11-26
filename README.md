# Portafolio-Maca

Portafolio visual de **MACA** — Visual Journal editorial.

**Producción:** https://portafolio.justcamm.online  

El contenido y las fotos viven **dentro del tema** (`assets/maca` + `inc/journal.php`). No depende de importar la base de datos.

## Despliegue en el VPS

```bash
ssh ubuntu@51.222.24.228
git -C ~/Portafolio-Maca pull || git clone https://github.com/GustavoUNAL/Portafolio-Maca.git ~/Portafolio-Maca
sudo bash ~/Portafolio-Maca/vps/setup-nginx-site.sh   # solo primera vez
sudo bash ~/Portafolio-Maca/vps/deploy-theme-only.sh
```

Detalle en [DEPLOY.md](DEPLOY.md).

## Repositorio

https://github.com/GustavoUNAL/Portafolio-Maca
