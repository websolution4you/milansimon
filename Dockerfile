# Použijeme oficiálny PHP obraz s Apache webserverom
FROM php:8.2-apache

# Google Cloud Run vyžaduje, aby aplikácia počúvala na porte špecifikovanom v premennej prostredia PORT (predvolene 8080)
ENV PORT=8080

# Nastavíme Apache, aby počúval na správnom porte
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Povolíme mod_rewrite pre tvoje pekné URL (aby fungovalo smerovanie v index.php)
RUN a2enmod rewrite

# Aby mod_rewrite fungoval, musíme povoliť .htaccess (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Skopírujeme tvoj kód z repozitára do kontajnera
COPY . /var/www/html/

# Nastavíme správne oprávnenia, aby server mohol čítať a servírovať súbory
RUN chown -R www-data:www-data /var/www/html
