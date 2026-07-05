FROM php:8.2-apache

# Dependencias del sistema y extensiones PHP que usa la app
# (pdo_mysql/mysqli -> DBAbstractModel.php, gd+webp -> ImageConverter.php)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libwebp-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli gd zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Límite de subida explícito, coherente con la validación de la app (escudos <= 2 MB)
RUN { \
        echo 'upload_max_filesize = 2M'; \
        echo 'post_max_size = 8M'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

# Composer (para instalar dependencias PHP dentro de la imagen)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Vhost con DocumentRoot -> public/ y AllowOverride para el .htaccess
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

COPY . .

# public/imgs/uploads recibe subidas de archivos en tiempo de ejecucion
RUN chown -R www-data:www-data /var/www/html/public/imgs/uploads \
    && find /var/www/html/public/imgs/uploads -type d -exec chmod 775 {} \;

EXPOSE 80
