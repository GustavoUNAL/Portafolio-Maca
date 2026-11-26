# Despliegue MACA → portafolio.justcamm.online

Servidor web: **Caddy** (no Nginx). SSL automático con Let's Encrypt.

## DNS

| Tipo | Nombre | Valor |
|------|--------|--------|
| A | `portafolio` | `51.222.24.228` |

URL: https://portafolio.justcamm.online

## Opción A — Desplegar EN el VPS (recomendado)

Conéctate por SSH y ejecuta:

```bash
ssh ubuntu@51.222.24.228
git clone https://github.com/GustavoUNAL/Portafolio-Maca.git ~/Portafolio-Maca
# si ya existe: git -C ~/Portafolio-Maca pull
sudo bash ~/Portafolio-Maca/vps/deploy-on-server.sh
```

Eso clona el repo, instala **Caddy** (apaga Nginx), copia tema + uploads a `/var/www/justcamm` y activa el tema.

Luego, para la base de datos completa (posts, páginas), desde tu Mac:

```bash
cd "/Users/gustavoarteaga0608gmail.com/Local Sites/portafolio"
./deploy.sh
```

## Opción B — Solo Caddy la primera vez

```bash
scp -r vps ubuntu@51.222.24.228:~/
ssh ubuntu@51.222.24.228
sudo bash ~/vps/setup-caddy.sh
```

Si `texno.site` también está en este VPS, descomenta su bloque en `/etc/caddy/Caddyfile` y:

```bash
sudo systemctl reload caddy
```

## Verificar

Abre: https://portafolio.justcamm.online
