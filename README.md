# 警告：有用户提示该镜像有挖矿病毒，作者没时间维护，不要再用了

# Docker LNMP 3.5

Docker LNMP 可以构建出基于 Docker 的 PHP 开发环境，其优势有在短时间内随意构建不同版本的相关服务、环境统一分布在不同服务器等，使开发者能够更专注于开发业务本身。

### 产品特色

* 灵活切换适合国内的源（apt-get、php composer）
* 组件精简易懂，学习、测试环境、生产环境均适合
* 可能是最易用的计划任务（安装在 Tools 组件里）
* 良好的扩展性

### 组件（容器）及相关软件版本

    Ningx：最新稳定版
    PHP：php-fpm 7.4
    MySQL：5.7
    Redis：最新稳定版
    Tools：Alpine latest，作为辅助工具容器如计划任务备份数据等

### 目录结构

    docker-lnmp
    |----/build                  镜像构建目录
    |----/work                   持久化目录
    |--------/components/        组件库
    |------------/component      组件，包括了数据、配置文件、日志等持久化数据
    |-----------------/config    组件的配置目录
    |-----------------/log       组件的日志目录
    |--------/wwwroot            WEB 文件目录
    |----/.env-example           配置文件
    |----/docker-compose.yml     docker compose 配置文件

## 开始安装

没有安装 Docker 的同学移步 [安装教程](https://github.com/exc-soft/docker-lnmp#安装-docker-及相关工具)，如果你有足够的时间强烈建议通读 [Docker — 从入门到实践](https://yeasy.gitbooks.io/docker_practice/content/)

    cd ~/
    git clone https://github.com/usoftopen/docker-lnmp.git

    cd docker-lnmp
    cp .env-example .env

    # 配置数据库密码、时区、端口等
    vim .env

    # 构建镜像并启动容器
    sudo docker-compose up --build -d

启动成功访问 http://localhost 即可

## 可能遇到的问题

如果下面的说明解决不了你的问题还可以联系我共同探讨（WeChat：18518732873，备注：Docker-LNMP）

### 常用操作命令

    # 查看当前启动的容器
    sudo docker-compose ps
    
    # 启动部分服务在后边加服务名，不加表示启动所有，-d 表示在后台运行
    sudo docker-compose up [nginx|php| ...] -d
    
    # 停止和启动类似
    sudo docker-compose stop [nginx|php| ...]

    # 停止并删除相关的容器
    sudo docker-compose down [nginx|php| ...]

    # 删除所有未运行的容器
    sudo docker rm $(sudo docker ps -a -q)

    # 删除所有未运行的镜像，-f 可以强制删除
    sudo docker rmi $(sudu docker images -q)

    # 重新构建过清理无效数据（注意如果执行 docker images -a 会出现一些 none 的镜像，这些是构建镜像的中间层不占用空间也不是垃圾数据，不用管，使用下面的命令就是清理无效数据）
    sudo docker system prune

更多可通过 `sudo docker -h` 或者 `sudo docker-compose -h` 查看

### 修改镜像文件怎么处理？
    
比如在 php 里新增一个扩展

    # 1、更改对应的 docker-lnmp/build/php/Dockerfile
    # 2、重新构建镜像
    sudo docker-compose build [php|...]

### 如何在 php 里连接 MySQL 和 Redis？

    <?php

        echo "<pre>";

        // 连接 MySQL
        $user = "root";
        $pass = "DockerLNMP";
        $dbh = new PDO('mysql:host=mysql;dbname=mysql', $user, $pass);
        
        foreach($dbh->query('SELECT * from user') as $row) {
            print_r($row);
        }

        echo "<br />";

        // 连接 Redis, 注意如果外网连接默认端口为6399
        $redis = new Redis();
        $redis->connect('redis', 6379);
        $redis->set("test-key","hello");
        echo "Stored string in redis:: " . $redis->get("test-key");

### 如何使用 Tools 组件里的计划任务？

直接在 /work/components/tools/cron.d 新建 crontab 文件或者使用自带的 task 就行了，比如做数据库备份计划，可以将备份的文件保存到 cron.d 同级 backup 目录下面，其中有几个坑都趟平了可以看下 task 文件里的注释说明，另外不用担心容器重启后计划任务停止，只要容器在运行你的任务就在运行。

### 其他的坑

如果需要升级某些组件的版本需要注意载入对应版本的配置文件，修改对应的配置信息，比如 redis.conf 默认配置的出口 ip 为 127.0.0.1，这样的话 php 的容器是连不上的，需要修改成 0.0.0.0，另外也要注意修改对应的 log path。

## 安装 Docker 及相关工具

### 安装 docker（2选1）
    
1、参考 daocloud 提供的文档（推荐）

    # 注意按照文档如果执行类似 install docker-ce=17.03.1* 出错，执行 install docker-ce 即可
    https://download.daocloud.io/Docker_Mirror/Docker

2、ubuntu 系统（可能不是最新版本的，适合学习或者测试用）

    apt-get update && apt install docker.io    
    
### 安装 docker-compose
    
    sudo curl -L "https://github.com/docker/compose/releases/download/1.23.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    
    sudo chmod +x /usr/local/bin/docker-compose

### 启动 Docker

    sudo service docker start
    sudo docker info

### 配置 DockerHub 加速器（你用的阿里云也许不用配置，如果觉得下载镜像很慢就配置）

阿里云加速器：每个人有对应的加速地址，访问 https://cr.console.aliyun.com ->【镜像加速器】配置加速器

DaoCloud 加速器：http://guide.daocloud.io/dcs/daocloud-9153151.html

腾讯云加速器：https://www.qcloud.com/document/product/457/7207

## 更新日志

### V3.5（2020-02-21）

* 重要更新，默认开启 opcache，接口性能至少提高 10 倍

### V3.4（2020-01-28）

* 升级了 php 版本到 7.4，修复了依赖相关错误
* 移除了 swoole 扩展支持
* 修改 .env 默认 redis 对外端口为 6399，建议自行更改并设置密码提升安全性

### V3.3（2019-07-13）

* 升级了 mysql 版本到 7.3
* 回退了 php 版本到 7.2.10，7.3 针对 gd 扩展有依赖问题

### V3.2（2019-04-29）

* 优化了 Dockerfile，精简构建的镜像层
* 去掉了启动 fpm 引导文件

### V3.1（2019-01-23）

* 新增 Tools 组件，用于放置计划任务等辅助服务
* 升级了部分组件及 PHP 扩展的版本
* crontab 与 PHP 组件解耦，放到 Tools 组件里
* 新增 Swoole 扩展
* 优化一些其他细节 ~

### License
MIT
