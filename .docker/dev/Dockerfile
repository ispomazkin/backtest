FROM php:7.4-fpm

ARG ENV
ENV COMPOSER_ALLOW_SUPERUSER 1

# install dependencies
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y \
    libxml2-dev \
    build-essential \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libwebp-dev \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libicu-dev \
    libmagickwand-dev \
    unzip \
    libzip-dev \
    zip \
    git \
    libpq-dev \
    procps \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-install soap \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) opcache \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) sockets \
    && apt-get -y install gcc make autoconf libc-dev pkg-config \
    && apt-get -y install zlib1g-dev \
    && apt-get -y install libmemcached-dev \
    && pecl install memcached && docker-php-ext-enable memcached \
    && apt-get clean && apt-get autoremove -y

WORKDIR /app

ADD . /app
#
#RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
#RUN composer install --prefer-dist --no-autoloader && rm -rf /root/.composer
#RUN composer dump-autoload --optimize


