FROM php:7.4-apache

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo_mysql sockets bcmath

# Включение mod_rewrite в Apache
RUN a2enmod rewrite

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копирование composer файлов сначала для кеширования зависимостей
COPY composer.json composer.lock ./

# Установка зависимостей Composer
RUN composer install --no-dev --optimize-autoloader

# Копирование остальных файлов проекта
COPY . .

# Установка прав на запись для папок, которые могут потребоваться
RUN chown -R www-data:www-data /var/www/html

# Установка DocumentRoot Apache в папку public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80