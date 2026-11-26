# Despliegue MACA → portafolio.justcamm.online

Usa el **Nginx que ya tienes** (PM2 y otros sitios no se tocan).  
Solo se añade un vhost nuevo y se hace `nginx reload`.

## DNS

| Tipo | Nombre | Valor |
|------|--------|--------|
| A | `portafolio` | `51.222.24.228` |

URL: https://portafolio.justcamm.online

## En el VPS

```bash
ssh ubuntu@51.222.24.228
git clone https://github.com/GustavoUNAL/Portafolio-Maca.git ~/Portafolio-Maca
# si ya existe: git -C ~/Portafolio-Maca pull
sudo bash ~/Portafolio-Maca/vps/deploy-on-server.sh
```

Eso:

1. Actualiza el repo  
2. Añade el sitio Nginx `portafolio.justcamm.online` (**sin apagar Nginx**)  
3. Copia tema + uploads a `/var/www/justcamm`  
4. Activa el tema  

## Base de datos completa (desde tu Mac)

```bash
cd "/Users/gustavoarteaga0608gmail.com/Local Sites/portafolio"
./deploy.sh
```

## Verificar

Abre: https://portafolio.justcamm.online  

Comprueba que tu app PM2 sigue en pie (`pm2 status`).
