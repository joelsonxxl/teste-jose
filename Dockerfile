FROM php:8.3.6-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

## Diretório da aplicação
ARG APP_DIR=/var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

### apt-utils é um extensão de recursos do gerenciador de pacotes APT
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    apt-utils \
    git \
    unzip \
    gnupg

RUN docker-php-ext-install pdo pdo_mysql

RUN rm -rf /etc/apache2/sites-enabled/000-default.conf
COPY ./apacheconfig/000-default.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR $APP_DIR
RUN chown www-data:www-data $APP_DIR
COPY --chown=www-data:www-data ./ .

RUN composer install --no-interaction
RUN php artisan key:generate

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN chown www-data:www-data -R *

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
