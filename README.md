# Welcome

[![Build Status](https://travis-ci.com/SlovakNationalGallery/webumenia.sk.svg?branch=master)](https://travis-ci.com/SlovakNationalGallery/webumenia.sk)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

[Web umenia](http://www.webumenia.sk) is an open platform to explore digitized art collections from public galleries and museums.

Web umenia is developed by [lab.SNG](http://lab.sng.sk/): the digital R&D lab of the Slovak National Gallery. We are working with public art organisations to make their art accessible and explorable online so curious people around the world can learn more about works of art and the context they've been created in.

Web umenia offers a user-friendly way to search and explore art from collections of multiple galleries and museums.

Digital reproductions of public domain artworks from several collections are available for download in high resolution for both personal and commercial use

Art organisation with digitized artworks organised in a Collection Management System can easily publish their art via CSV imports or OAI-PMH harvests. If you would like to know more about using Web umenia in your organisation, contact us via [lab@sng.sk](mailto:lab@sng.sk)

Developers interested to build applications on top of Web umenia can use our API. See our [wiki on GitHub](https://github.com/SlovakNationalGallery/web-umenia-2/wiki/ElasticSearch-Public-API) for more info.

# Contributing

We greatly encourage others to get involved! See [our contributing guidelines](CONTRIBUTING.md) for more info about different ways to contribute to Web umenia.

We are committed to providing a welcoming and inspiring community for all and expect others who participate in the project to honour our [code of conduct](CODE_OF_CONDUCT.md).

# Tech setup

## Requirements

This software is built with the [Laravel framework](http://laravel.com/).

It requires
* PHP 7.4.x
* MySQL
* Elasticsearch

## Installation with Docker

This requires docker-compose
these steps will set up a linked system of 4 containers:
	web service (nginx webserver)
	php service (contains our application code)
	database container -- CAVEAT: don't use 'root' user for db, .env.example has sample username / password
		when you first build the stack the mysql dockerfile builds a new database and creates a specific user
		with which web_umenia accesses the db, which is as it should be. You may still access the db as 'root' yourself through
		elasticsearch container
that will communicate internally with one another

1. Clone this repository.
    ```
    git@github.com:SlovakNationalGallery/web-umenia-2.git webumenia/
    cd webumenia/
    ```
2. create a .env file (you can use the included env.example as a base)
3. build the whole stack (mysql, elasticsearch, laravel php app + nginx server)
with docker-compose:
	```
	docker-compose build
	```
	the first time you do this it will take a while, a lot of different components
need to be fetched from remote servers.
Be patient, subsequent builds won't take nearly as long.
    - choose a database - you can set $DB_DATABASE in the environment and switch between different
variants by editing that variable.
4. start the app
	```
	docker-compose up
	```
	or
	```
	docker-compose up -d
	```
	to run it in the background.
	(In this case you can watch the output of a component like this: `docker-compose logs -f php`)
5. install dependencies
	```
	docker-compose exec php composer install --no-plugins --no-scripts --no-interaction
	```
5. run migrations
	```
	docker-compose exec php php artisan migrate --seed
	```
6. setup elasticsearch
	```
	docker-compose exec php php artisan es:setup
	```
7. visit http://localhost:8080 in your browser to have a look

to stop the dockerized application: `docker-compose down`

## Running tests

Create `.env.testing` file (you can use the included `.env.testing.example` as a base) and run:
```
docker-compose -f docker-compose.yml -f docker-compose.test.yml run php
```


## Harvesting Data

You can now fill artworks by adding them manually or importing using the "spice harvester" (harvests using OAI-PMH protocol) using `php artisan oai-pmh:harvest` and choosing to harvest `1 [item] Europeana SNG`. Or login to admin at `http://yourlocalhost/admin` using default credentials `admin`/`admin` and go to `Import` -> `Spice Harvester` -> `Spustiť`.

## IIPImage

This application uses [IIPImage server](http://iipimage.sourceforge.net/) for zoomable (and downloadable) images.

IIPImage must be seen locally, what can be achieved using a ProxyForwarding.

To enable image server for default oai set (Europeana), put this lines of code into your virtualhost setup:

```
ProxyPass /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi

ProxyPassReverse /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi
```

## Compiling Assets

We use [Laravel Mix](https://laravel-mix.com/) to compile styles imported into a [main file](resources/less/style.less) into a single CSS file:

```
npm install && npm run dev
```

For a better developer experience, use `npm run watch` to watch a directory for changes and automatically compile LESS into CSS.

## Maintainer

This project is maintained by [lab.SNG](http://lab.sng.sk). If you have any questions please don't hesitate to ask them by creating an issue or email us at [lab@sng.sk](mailto:lab@sng.sk).

# License

Source code in this repository is licensed under the MIT license. Please see the [License File](LICENSE) for more information.
