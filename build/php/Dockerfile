FROM php:7.2.10-fpm

COPY resource /home/resource

ARG CHANGE_SOURCE=false
ARG TIME_ZONE=UTC

ENV TIME_ZONE=${TIME_ZONE} LC_ALL=C.UTF-8

RUN \
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
        composer config -g repo.packagist composer https://packagist.laravel-china.org ; \
    fi; \
    \
    # ⬇ 更新、安装基础组件
    apt-get update && apt-get install -y --allow-remove-essential --no-install-recommends \
    procps \
    libtinfo5 \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmcrypt-dev \
    libmhash-dev \
    ntpdate \
    cron;

####################################################################################
# 安装 PHP 扩展
####################################################################################

RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ ; \
    docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli pgsql pdo_pgsql soap ; \
    \
    # ⬇ Redis
    pecl install /home/resource/redis-4.2.0.tgz ; \ 
    echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini ; \
    \
    # ⬇ Swoole
    pecl install /home/resource/swoole-4.2.12.tgz ; \
    echo "extension=swoole.so" > /usr/local/etc/php/conf.d/swoole.ini ; \
    \
    # ⬇ Mcrypt
    pecl install /home/resource/mcrypt-1.0.2.tgz ; \
    echo "extension=mcrypt.so" > /usr/local/etc/php/conf.d/mcrypt.ini ; \
    \
    # ⬇ YAF
    tar xzvf /home/resource/yaf-3.0.7.tgz && cd yaf-3.0.7 ; \
    /usr/local/bin/phpize && ./configure && make && make install ; \
    \
    # ⬇ 清理
    rm -rf /var/lib/apt/lists/* ; \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false ; \
    rm -rf /home/resource ;

