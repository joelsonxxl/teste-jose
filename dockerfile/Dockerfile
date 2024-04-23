FROM php:8.3.6-fpm

## Diretório da aplicação
ARG APP_DIR=/var/www/html

### apt-utils é um extensão de recursos do gerenciador de pacotes APT
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    apt-utils \
    gnupg

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql pgsql session xml

RUN docker-php-ext-install zip iconv simplexml pcntl gd fileinfo

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR $APP_DIR
RUN cd $APP_DIR
RUN chown www-data:www-data $APP_DIR
COPY . $APP_DIR
RUN chmod -R 777 *
RUN composer install --no-interaction
RUN php artisan key:generate


RUN apt-get clean && rm -rf /var/lib/apt/lists/*

EXPOSE 5000

CMD ["php", "/var/www/html/artisan", "serve —port=5000"]
