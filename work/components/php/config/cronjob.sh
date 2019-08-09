#!/usr/bin/env bash

## cronJob on/off

CRON_STATUS=${1}

if [ ! -f "/etc/cron.d/cron-php" ]; then
    echo "[ERROR]  /etc/cron.d/cron-php NOT EXISTS !"
    exit
fi

crontab /etc/cron.d/cron-php

if [ "$CRON_STATUS" = "restart" ]; then

    /etc/init.d/cron restart
    echo " crontab restart finish."
    exit
fi

if [  "$CRON_STATUS" = "start" ]; then
    /etc/init.d/cron start
    echo " crontab start finish."
    exit
fi

if [  "$CRON_STATUS" = "stop" ]; then
    /etc/init.d/cron stop
    echo " crontab stop finish."
    exit
fi

echo "[ERROR] Miss Paramter !";
exit
