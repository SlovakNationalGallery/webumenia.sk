#!/bin/bash
ES_PATH=../elasticsearch-2.4.1
DATE=`date +%Y-%m-%d`
trap 'kill %1; kill %2' SIGINT
exec $ES_PATH/bin/elasticsearch | tee ./storage/logs/laravel-$DATE.log | sed -e 's/^/[elasticsearch] /' & \
php artisan serve | tee ./storage/logs/elasticsearch-$DATE.log | sed -e 's/^/[web_umenia] /' 
