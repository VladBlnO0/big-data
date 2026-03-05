FROM php:latest

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libcurl4-gnutls-dev \
    && docker-php-ext-install pdo pdo_pgsql curl

WORKDIR /var/www/html

COPY /src/ .