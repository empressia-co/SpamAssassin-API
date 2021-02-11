FROM registry.empressia.pl/docker/php:7.4-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN apt-get update && apt-get install -y --no-install-recommends libssl-dev \
    && pecl install mongodb && docker-php-ext-enable mongodb
