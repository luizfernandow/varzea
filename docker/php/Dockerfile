FROM php:7.4-fpm-buster

WORKDIR /var/www/html

# Set timezone
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt install -y vim libmcrypt-dev zlib1g-dev libpq-dev libpng-dev libxml2-dev libgmp-dev libzip-dev libssl-dev \
    && apt-get clean
RUN docker-php-ext-install gmp pdo_pgsql zip pcntl \
    && pecl install -o -f redis \
    && pecl install -o -f crypto \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis crypto \
    && cd /usr/src \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# NODE
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash \
    && apt-get install -y nodejs \
    && apt-get clean

    # unzip