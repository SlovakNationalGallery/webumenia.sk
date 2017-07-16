# Web Umenia

This is the repository of portal [www.webumenia.sk](http://www.webumenia.sk). 
The site is entirely open source and community involvement is encouraged.

## Requirements

This software is built with [Laravel5 framework](http://laravel.com/).

It requires
* PHP 5.5.9+
* MySQL
* Elasticsearch

## Local Installation

Here are the steps for installation on a local machine.

1. Clone this repository.
    ```
    git@github.com:SlovakNationalGallery/web-umenia-2.git webumenia/
    cd webumenia/
    ```
2. setup database in your favourite database editor. set:
    * db name
    * username
    * password
3. configure access to your database in `/app/config/database.php` 
4. set `.env` file. you can copy values from `.env.example`
5. Run `composer install` to fulfil required libraries. 
6. Make sure elasticsearch is running. you can set the index name in `app/config/app.php`
7. Run migrations to setup the database with `php artisan migrate --seed` 

### Harvesting Data

You can now fill artworks by adding them manually or importing using the "spice harvester" (harvests using OAI-PMH protocol) using `php artisan oai-pmh:harvest` and choosing to harvest `1 [item] Europeana SNG`. Or login to admin at `http://yourlocalhost/admin` using default credentials `admin`/`admin` and go to `Spice Harvester` -> 'Spustit'.

### IIPImage

This application uses [IIPImage server](http://iipimage.sourceforge.net/) for zoomable (and downloadable) images.

IIPImage must be seen localy, what can be achieved using a ProxyForwarding.

To enable image server for default oai set (Europeana), put this lines of code into your virtualhost setup:

```
ProxyPass /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi

ProxyPassReverse /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi
```

### Updating Elastic Search

* neccesary steps are specified in the file `ES2_migration_steps.txt`
* command to generate ES2 compatibile index:
`php artisan es:setup`
* command to reindex data to the index
`php artisan es:reindex`

## Maintainer

This project is maintained by [lab.SNG](http://lab.sng.sk). If you have any questions please don't hesitate to ask them by creating an issue or email us at [lab@sng.sk](mailto:lab@sng.sk).

## License

This is free software released into the [public domain](http://unlicense.org/). Please see [License File](LICENSE) for more information.
