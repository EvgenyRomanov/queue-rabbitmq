FROM php:8.3-cli-alpine AS cli

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

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

USER app



FROM cli AS worker

CMD ["php", "/app/bin/console.php", "worker:run", "default", "queue_test,test123,queue2,default"]
