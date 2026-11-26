# Portafolio-Maca

Portafolio visual de **MACA** — Visual Journal editorial en WordPress (Local → VPS).

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

1. Completa `deploy.env` (ya apunta a `portafolio.texno.site`)
2. En el VPS debe existir WordPress en `/var/www/portafolio-maca`
3. Desde esta carpeta:

```bash
./deploy.sh
```

Eso sube tema + uploads + base de datos y reemplaza `http://portafolio.local` → `https://portafolio.texno.site`.

## Repositorio

https://github.com/GustavoUNAL/Portafolio-Maca
