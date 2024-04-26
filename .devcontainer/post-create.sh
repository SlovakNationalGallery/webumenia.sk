#!/bin/sh

docker run -d \
    -p 9200:9200 \
    -e discovery.type=single-node \
    -e ES_JAVA_OPTS="-Xms1g -Xmx1g" \
    ghcr.io/slovaknationalgallery/elasticsearch-webumenia:7.17.3

docker run -d \
    -p 3306:3306 \
    -e MYSQL_DATABASE=webumenia_development \
    -e MYSQL_ALLOW_EMPTY_PASSWORD=1 \
    mysql:8.0.36