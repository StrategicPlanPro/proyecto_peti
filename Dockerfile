# Usamos la imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiamos los archivos del proyecto al directorio web de Apache
COPY ./src /var/www/html

# Copiamos el archivo de configuración custom.conf
COPY ./custom.conf /etc/apache2/conf-available/custom.conf

# Habilitamos la configuración de custom
RUN a2enconf custom

# Configuramos permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponemos el puerto 80
EXPOSE 80

# Iniciamos Apache en primer plano
CMD ["apache2-foreground"]
