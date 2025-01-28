FROM php:8.2-fpm

# Instala extensiones y limpiar caché
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalo Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalo herramientas globales
RUN composer global require phpunit/phpunit:^10 phpstan/phpstan:^1.10

# directorio de trabajo
WORKDIR /var/www/siroko-cart

# Copia archivos del proyecto
COPY . .

# Instala dependencias de Symfony
RUN composer install --no-dev --optimize-autoloader

# Configura permisos
RUN chown -R www-data:www-data /var/www/siroko-cart

EXPOSE 9000

CMD ["php-fpm"]

