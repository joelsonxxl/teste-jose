# start with the official Composer image and name it
FROM composer:latest AS composer

FROM php:8.3.6-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

## Diretório da aplicação
ARG APP_DIR=/var/www/html

### apt-utils é um extensão de recursos do gerenciador de pacotes APT
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    apt-utils \
    git \
    unzip \
    gnupg

RUN docker-php-ext-install pdo pdo_mysql

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# show that both Composer and PHP run as expected
RUN composer --version && php -v

WORKDIR $APP_DIR
RUN chown www-data:www-data $APP_DIR
COPY --chown=www-data:www-data ./ .
RUN composer install --no-interaction
RUN chown www-data:www-data $APP_DIR
COPY --chown=www-data:www-data ./ .
RUN php artisan key:generate

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN chown www-data:www-data -R *

EXPOSE 5000

CMD ["/usr/local/bin/php /var/www/html/artisan serve --host=0.0.0.0 -—port=5000"]
