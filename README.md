# Portafolio-Maca

Portafolio visual de **MACA** — Visual Journal editorial en WordPress.

**Producción:** https://portafolio.justcamm.online  
**Servidor:** Nginx (vhost nuevo; no se apaga el servicio — PM2 intacto)

## Identidad

| Elemento | Valor |
|----------|--------|
| Header | Negro `#050308` |
| Fondo | Púrpura `#3C183C` |
| Texto / acentos | Verde lima `#CDFC8A` |
| Tipografía display | Fraunces |
| Tipografía cuerpo | Manrope |
| Tema | `nasly` |

## Contenido (Visual Journal)

1. Diseño como herramienta de comunicación  
2. Sobre mí  
3. Mi proceso creativo  
4. La importancia de construir una identidad visual  
5. Ilustración y narrativa visual (+ 3D)  
6. Collage digital como medio de expresión emocional  

Páginas: Visual Journal · Sobre mí · Contacto  

## Local

Sitio: `http://portafolio.local`  
Tema activo: **nasly**

## Despliegue al VPS

1. DNS: A `portafolio` → `51.222.24.228`
2. En el VPS: `sudo bash ~/Portafolio-Maca/vps/deploy-on-server.sh`
3. Desde la Mac (DB completa): `./deploy.sh`

Detalle en [DEPLOY.md](DEPLOY.md).

## Repositorio

https://github.com/GustavoUNAL/Portafolio-Maca
