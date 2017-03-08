FROM php:7.1-cli
RUN pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini
RUN docker-php-ext-install pcntl
STOPSIGNAL SIGKILL
WORKDIR /opt
