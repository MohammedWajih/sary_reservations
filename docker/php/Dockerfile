FROM php:8.0-fpm

LABEL maintainer="mwageeeh@gmail.com"

RUN apt-get update \
    && apt-get install -y vim  \
    && apt-get install -y zip  \
    # Install Postgre PDO
    && set -ex  apk --no-cache add postgresql-libs postgresql-dev \
    && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    ## Install Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version \
    && composer require predis/predis 



EXPOSE 9000
CMD [ "sh","-c","cd /var/www/html && php artisan migrate:refresh --seed  && php-fpm"] 
