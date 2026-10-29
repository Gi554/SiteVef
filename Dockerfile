# Les dépendances et les ressources sont construites sur le runner, pas sur le VPS.
FROM php:8.3-apache-bookworm AS php-base
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev libzip-dev libonig-dev libsqlite3-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure opcache --disable-opcache-jit \
    && docker-php-ext-install -j2 intl mbstring pdo_sqlite pdo_mysql zip gd pcntl opcache \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

FROM php-base AS dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --no-progress

FROM node:22-bookworm-slim AS assets
WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY --from=dependencies /var/www/html/vendor ./vendor
COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

FROM php-base AS application
ARG VEF_REVISION=local
LABEL org.opencontainers.image.source="https://github.com/Gi554/SiteVef" \
    org.opencontainers.image.revision="${VEF_REVISION}"
COPY . .
COPY --from=dependencies /var/www/html/vendor ./vendor
COPY --from=assets /build/public/build ./public/build
RUN mkdir -p storage/app/private storage/app/public storage/framework/cache/data \
    storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    public/Style1/imagesProjets public/Style1/imagesMembres \
    public/Style1/imagesActualites public/Style1/imagesParrainage \
    && composer dump-autoload --no-dev --classmap-authoritative --no-scripts \
    && php artisan package:discover --ansi \
    && a2enmod rewrite \
    && sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && printf '%s\n' '<VirtualHost *:8080>' \
       'DocumentRoot /var/www/html/public' \
       '<Directory /var/www/html/public>' 'AllowOverride All' 'Require all granted' '</Directory>' \
       'ErrorLog /proc/self/fd/2' 'CustomLog /proc/self/fd/1 combined' '</VirtualHost>' \
       > /etc/apache2/sites-available/000-default.conf \
    && printf '%s\n' '<IfModule mpm_prefork_module>' 'StartServers 2' 'MinSpareServers 2' \
       'MaxSpareServers 4' 'ServerLimit 8' 'MaxRequestWorkers 8' 'MaxConnectionsPerChild 1000' \
       '</IfModule>' > /etc/apache2/conf-available/vef-limits.conf \
    && a2enconf vef-limits \
    && printf '%s\n' 'expose_php=Off' 'display_errors=Off' 'log_errors=On' \
       'upload_max_filesize=4M' 'post_max_size=64M' 'memory_limit=256M' \
       > /usr/local/etc/php/conf.d/vef.ini \
    && chown -R www-data:www-data storage bootstrap/cache \
       /var/run/apache2 /var/lock/apache2 /var/log/apache2
USER www-data
EXPOSE 8080
HEALTHCHECK --interval=15s --timeout=5s --start-period=30s --retries=4 \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1:8080/up") === false ? 1 : 0);'
CMD ["apache2-foreground"]
