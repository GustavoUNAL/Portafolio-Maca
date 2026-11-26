# Despliegue MACA → portafolio.justcamm.online

El Visual Journal va **embebido en el tema** (fotos + textos).  
**No hace falta importar la base de datos** para ver el contenido.

Nginx se mantiene (PM2 intacto). Solo se añade/actualiza el vhost y el tema.

## DNS

| Tipo | Nombre | Valor |
|------|--------|--------|
| A | `portafolio` | `51.222.24.228` |

## En el VPS (contenido + fotos)

```bash
ssh ubuntu@51.222.24.228
git clone https://github.com/GustavoUNAL/Portafolio-Maca.git ~/Portafolio-Maca
# si ya existe:
git -C ~/Portafolio-Maca pull

# Primera vez (vhost Nginx, sin apagar el servicio):
sudo bash ~/Portafolio-Maca/vps/setup-nginx-site.sh

# Siempre que actualices:
sudo bash ~/Portafolio-Maca/vps/deploy-theme-only.sh
```

## Verificar

- https://portafolio.justcamm.online  
- https://portafolio.justcamm.online/visual-journal/  
- Entradas con imágenes y el GIF de proceso  

```bash
pm2 status
sudo systemctl status nginx --no-pager | head -8
```
