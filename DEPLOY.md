# Despliegue MACA → portafolio.justcamm.online

Servidor web: **Caddy** (no Nginx). SSL automático con Let's Encrypt.

## DNS

| Tipo | Nombre | Valor |
|------|--------|--------|
| A | `portafolio` | `51.222.24.228` |

URL: https://portafolio.justcamm.online

## 1) Primera vez en el VPS (Caddy)

Desde tu Mac:

```bash
cd "/Users/gustavoarteaga0608gmail.com/Local Sites/portafolio"
scp -r vps ubuntu@51.222.24.228:~/
ssh ubuntu@51.222.24.228
sudo bash ~/vps/setup-caddy.sh
```

Eso detiene Nginx, instala Caddy y sirve WordPress en `/var/www/justcamm`.

Si `texno.site` también está en este VPS, descomenta su bloque en `/etc/caddy/Caddyfile` y:

```bash
sudo systemctl reload caddy
```

## 2) Desplegar el sitio (tema + DB + medios)

```bash
cd "/Users/gustavoarteaga0608gmail.com/Local Sites/portafolio"
./deploy.sh
```

Reemplaza `http://portafolio.local` → `https://portafolio.justcamm.online`.

## Verificar

Abre: https://portafolio.justcamm.online
