FROM ubuntu:18.04

ARG CHANGE_SOURCE=false

ARG TIME_ZONE=UTC
ENV TIME_ZONE=${TIME_ZONE} LC_ALL=C.UTF-8 DEBIAN_FRONTEND=noninteractive

COPY sources.list /etc/apt/china.sources.list

RUN if [ ${CHANGE_SOURCE} = true ]; then \
        mv /etc/apt/sources.list /etc/apt/source.list.bak ; \
        mv /etc/apt/china.sources.list /etc/apt/sources.list ; \
    fi; \
    \
    apt-get update && apt-get install -y --no-install-recommends \
    tzdata \
    procps \
    mysql-client \
    ntpdate \
    cron \
    vim \
    unzip \
    git \
    wget ; \
    rm -rf /var/lib/apt/lists/* ; \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false ; \
    \
    ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime ; \
    echo $TIME_ZONE > /etc/timezone ; \
    touch /var/log/cron.log ;

CMD /etc/init.d/cron start && tail -f /var/log/cron.log
