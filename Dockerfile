FROM php:8.2-apache

# Habilitar mod_rewrite para que funcionen las rutas en index.php
RUN a2enmod rewrite

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar la configuración del VirtualHost (si decides usar subdominios o configuraciones especiales)
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Copiar el código fuente
COPY . /var/www/html/

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html
