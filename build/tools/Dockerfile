FROM ubuntu:latest

# 默认不改变 apt-get 源
ARG CHANGE_SOURCE=false

# 设置时区
ARG TIME_ZONE=UTC
ENV DEBIAN_FRONTEND noninteractive
ENV TIME_ZONE ${TIME_ZONE}

# 替换 apt-get 源
COPY sources.list /etc/apt/china.sources.list
RUN if [ ${CHANGE_SOURCE} = true ]; then \
	mv /etc/apt/sources.list /etc/apt/source.list.bak && \
    mv /etc/apt/china.sources.list /etc/apt/sources.list \
;fi

RUN apt-get update && apt-get -y upgrade

# 安装基础组件
RUN apt-get install -y --no-install-recommends \
    tzdata procps mysql-client ntpdate cron vim unzip git wget

# 修改时区（依赖 tzdata）
RUN ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime && echo $TIME_ZONE > /etc/timezone

ENV LC_ALL C.UTF-8

ENTRYPOINT ["/bin/bash", "/home/start.sh"]