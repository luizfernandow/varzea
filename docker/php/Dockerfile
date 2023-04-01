FROM php:8.2-fpm-bullseye as dependencies

WORKDIR /var/www/html

# Set timezone
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt install -y vim unzip libmcrypt-dev zlib1g-dev libpq-dev libpng-dev libxml2-dev libgmp-dev libzip-dev libssl-dev \
    && apt-get clean

RUN docker-php-ext-install gmp pdo_pgsql zip pcntl \
    && pecl install -o -f redis \
    && pecl install -o -f crypto \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis crypto \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && cd /usr/src \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# NODE
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash \
    && apt-get install -y nodejs \
    && apt-get clean

FROM dependencies as development

ARG USER_ID
ARG GROUP_ID

RUN groupadd -o -g $GROUP_ID -r user \
    && adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID user

USER user

#PRODUCTION IMAGE
FROM dependencies as production
WORKDIR /var/www/html
COPY ../application /var/www/html
RUN composer install --optimize-autoloader --no-dev