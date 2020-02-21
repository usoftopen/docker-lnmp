# 注意：更新对应版本的 php 检查更新对应替换的源是否一致，否则后续会有很多扩展安装失败
FROM php:7.4-fpm

COPY resource /home/resource

ARG CHANGE_SOURCE=false
ARG TIME_ZONE=UTC

ENV TIME_ZONE=${TIME_ZONE} LC_ALL=C.UTF-8

RUN set -eux; \
    # ⬇ 修改时区
    ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime ; \ 
    echo $TIME_ZONE > /etc/timezone ; \
    \
    # ⬇ 安装 PHP Composer
    mv /home/resource/composer.phar /usr/local/bin/composer ; \
    chmod 755 /usr/local/bin/composer ; \
    \
    # ⬇ 替换源
    rm -rf /etc/apt/sources.list.d/buster.list ; \
    if [ ${CHANGE_SOURCE} = true ]; then \
        mv /etc/apt/sources.list /etc/apt/source.list.bak; \ 
        mv /home/resource/sources.list /etc/apt/sources.list; \
        composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ ; \
    fi; \
    \
    # ⬇ 更新、安装基础组件
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --allow-remove-essential --no-install-recommends \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libonig-dev \
    libmagickwand-dev \
    procps \
    libmhash-dev \
    ntpdate \
    git \
    zip \
    unzip \
    cron;

####################################################################################
# 安装 PHP 扩展
####################################################################################

RUN docker-php-ext-configure gd \
    --prefix=/usr \
    --with-freetype\
    --with-jpeg; \
    docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli pgsql pdo_pgsql soap pcntl sockets ; \
    docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install opcache ; \
    \
    # ⬇ Imagick
    pecl install imagick && docker-php-ext-enable imagick; \
    # ⬇ Redis
    pecl install /home/resource/redis-4.2.0.tgz ; \ 
    echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini ; \
    \
    # ⬇ Mcrypt
    pecl install /home/resource/mcrypt-1.0.3.tgz ; \
    echo "extension=mcrypt.so" > /usr/local/etc/php/conf.d/mcrypt.ini ; \
    \
    # ⬇ 清理
    rm -rf /var/lib/apt/lists/* ; \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false ; \
    rm -rf /home/resource ;
