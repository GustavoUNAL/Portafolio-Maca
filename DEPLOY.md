# Despliegue MACA → VPS

## Requisitos en el VPS

- WordPress instalado en `/var/www/portafolio-maca`
- Dominio `portafolio.texno.site` apuntando a `51.222.24.228`
- Usuario SSH `ubuntu` con `sudo`
- PHP 8.4-FPM + Nginx (ya configurados)

## Desde tu Mac (recomendado)

```bash
cd "/Users/gustavoarteaga0608gmail.com/Local Sites/portafolio"
./deploy.sh
```

El script:

1. Exporta la base de datos de Local  
2. Sube el tema `nasly` + `uploads`  
3. Importa el SQL en el VPS  
4. Reemplaza `http://portafolio.local` → `https://portafolio.texno.site`  
5. Activa el tema y limpia caché  

Te pedirá la contraseña SSH de `ubuntu@51.222.24.228`.

## Alternativa manual

```bash
# 1) Subir SQL
scp .deploy/maca-latest.sql ubuntu@51.222.24.228:~/maca.sql

# 2) En el VPS
cd /tmp && git clone https://github.com/GustavoUNAL/Portafolio-Maca.git
# o: cd /tmp/Portafolio-Maca && git pull
sudo rsync -a --delete Portafolio-Maca/app/public/wp-content/themes/nasly/ /var/www/portafolio-maca/wp-content/themes/nasly/
sudo rsync -a Portafolio-Maca/app/public/wp-content/uploads/ /var/www/portafolio-maca/wp-content/uploads/
sudo chown -R www-data:www-data /var/www/portafolio-maca/wp-content
cd /var/www/portafolio-maca
sudo -u www-data wp db import ~/maca.sql
sudo -u www-data wp search-replace 'http://portafolio.local' 'https://portafolio.texno.site' --all-tables
sudo -u www-data wp theme activate nasly
sudo -u www-data wp rewrite flush
```

## Verificar

Abre: https://portafolio.texno.site  
Debe verse MACA, Visual Journal y las 6 entradas.
