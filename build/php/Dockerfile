FROM php:7.3-fpm

# 拷贝需要用到的资源
COPY resource /home/resource

# 默认不改变 apt-get 源
ARG CHANGE_SOURCE=false

# 修改时区
ARG TIME_ZONE=UTC
ENV TIME_ZONE ${TIME_ZONE}
RUN ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime && echo $TIME_ZONE > /etc/timezone

# 安装 PHP Composer
RUN mv /home/resource/composer.phar /usr/local/bin/composer && \
    chmod 755 /usr/local/bin/composer

# 替换源
RUN rm -rf /etc/apt/sources.list.d/buster.list
RUN if [ ${CHANGE_SOURCE} = true ]; then \
    # ↓↓↓↓↓↓ apt-get ↓↓↓↓↓↓ #
    mv /etc/apt/sources.list /etc/apt/source.list.bak && \
    mv /home/resource/sources.list /etc/apt/sources.list && \
    # ↓↓↓↓↓↓ composer ↓↓↓↓↓↓ #
    composer config -g repo.packagist composer https://packagist.phpcomposer.com \
;fi

# 更新、安装基础组件
RUN apt-get update && apt-get -y upgrade && \
    apt-get install -y --no-install-recommends \
    procps libpq-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev  ntpdate \
    cron vim unzip git wget

####################################################################################
# 安装 PHP 扩展
####################################################################################

RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install gd pdo_mysql mysqli pgsql pdo_pgsql

# Redis
RUN pecl install /home/resource/redis-4.2.0.tgz && \ 
    echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

# Swoole
RUN pecl install /home/resource/swoole-4.2.12.tgz && \
    echo "extension=swoole.so" > /usr/local/etc/php/conf.d/swoole.ini

# Mcrypt
RUN apt-get install -y libmcrypt-dev libmhash-dev && \
    pecl install /home/resource/mcrypt-1.0.2.tgz && \
	echo "extension=mcrypt.so" > /usr/local/etc/php/conf.d/mcrypt.ini

ENV LC_ALL C.UTF-8

ENTRYPOINT ["/bin/bash", "/home/start.sh"]