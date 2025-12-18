FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    postgresql-client \
    postgresql-dev \
    bash \
    curl \
    git \
    nano \
    htop \
    icu-dev \
    libzip-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        bcmath \
        mbstring \
        intl \
        zip \
        pcntl \
        opcache \
        gd \
        xml \
        soap \
        sockets \
    && docker-php-ext-enable \
        pdo_pgsql \
        pgsql \
        opcache

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /app
