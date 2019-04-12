FROM php:7.1-fpm-jessie

WORKDIR /var/www/html

# Set timezone
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt install -y vim postgresql-client postgresql-contrib libmcrypt-dev zlib1g-dev libpq-dev libpng-dev libxml2-dev libgmp-dev \
    && apt-get clean \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install gmp pdo_pgsql pgsql mcrypt zip json pcntl \
    && pecl install -o -f redis \
    && pecl install -o -f crypto \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis crypto \
    && cd /usr/src \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# NODE
RUN curl -sL https://deb.nodesource.com/setup_9.x | bash \
    && apt-get install -y nodejs \
    && apt-get clean