# Welcome
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
* PHP 8.1
* MySQL 5.7
* Elasticsearch 7.3

We also provide a [Dockerfile](Dockerfile) and [docker-compose.yml](docker-compose.yml) with a basic stack set-up.

## First-time setup
In addition to the standard Laravel steps ([DB migrations](https://laravel.com/docs/8.x/migrations#running-migrations), [compiling assets](https://laravel.com/docs/8.x/mix#installation)), you'll so need to run the following once:

Link storage (for file uploads)
```
php artisan storage:link
```

Set up Elasticsearch indices:
```
php artisan es:setup
```

## Harvesting Data

You can fill artworks by adding them manually or importing using the "spice harvester" (harvests using OAI-PMH protocol) using `php artisan oai-pmh:harvest` and choosing to harvest `1 [item] Europeana SNG`. Or login to admin at `http://localhost:8000/admin` using default credentials `admin`/`admin` and go to `Import` -> `Spice Harvester` -> `Spustiť`.

## IIPImage

This application uses [IIPImage server](http://iipimage.sourceforge.net/) for zoomable (and downloadable) images.

IIPImage must be seen locally, what can be achieved using a ProxyForwarding.

To enable image server for default oai set (Europeana), put this lines of code into your virtualhost setup:

```
ProxyPass /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi

ProxyPassReverse /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi
```

## Maintainer

This project is maintained by [lab.SNG](http://lab.sng.sk). If you have any questions please don't hesitate to ask them by creating an issue or email us at [lab@sng.sk](mailto:lab@sng.sk).

# License

Source code in this repository is licensed under the MIT license. Please see the [License File](LICENSE) for more information.
