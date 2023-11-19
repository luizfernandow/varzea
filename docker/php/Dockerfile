FROM php:8.2-fpm-bullseye as dependencies

WORKDIR /var/www/html

# Set timezone
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt install -y vim unzip curl gnupg ca-certificates libmcrypt-dev zlib1g-dev libpq-dev libpng-dev libxml2-dev libgmp-dev libzip-dev libssl-dev \
    && apt-get clean

RUN docker-php-ext-install gmp pdo_pgsql zip pcntl opcache \
    && pecl install -o -f redis \
    && pecl install -o -f crypto \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis crypto \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && cd /usr/src \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/etc/php/php.ini "$PHP_INI_DIR/conf.d/php.ini"

FROM dependencies as development

# NODE
RUN mkdir -p /etc/apt/keyrings; \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
     | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg; \
    NODE_MAJOR=20; \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" \
     > /etc/apt/sources.list.d/nodesource.list; \
    apt-get -qy update; \
    apt-get -qy install nodejs \
    && apt-get clean

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./docker/etc/php/opcache_dev.ini "$PHP_INI_DIR/conf.d/opcache.ini"

ARG USER_ID
ARG GROUP_ID

RUN groupadd -o -g $GROUP_ID -r user \
    && adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID user

USER user

COPY ./docker/.bashrc /home/user/.bashrc

#PRODUCTION IMAGE
FROM dependencies as production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/etc/php/opcache_prod.ini "$PHP_INI_DIR/conf.d/opcache.ini"
WORKDIR /var/www/html
COPY ../application /var/www/html
RUN composer install --optimize-autoloader --no-dev