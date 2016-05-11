# Web Umenia 2

_This is Laravel5 migration branch._

This is the repository of new version of portal "web umenia" (web of art). The site is entirely open source and community involvement is encouraged.

Demo version -> [dvekrajiny.sng.sk](http://dvekrajiny.sng.sk)


## Requirements

This software is build upon [Laravel4 framework](http://laravel.com/).

It requires
* PHP 5.4+
* MySQL
* Elasticsearch


## Local Installation

Here are the steps for installation on a local machine.

1. Clone this repository.

    ```
    git@github.com:SlovakNationalGallery/web-umenia-2.git webumenia/
    cd webumenia/
    ```

2. configure access to your database in `/app/config/database.php` (*or `app/config/local/database.php` - depending on your setup in `bootstrap/start.php`*)
3. make sure elasticsearch is running. you can set the index name in `app/config/app.php`
3. Run `composer install`
5. Run the migration with `php artisan migrate --seed` 
6. Login to admin at `http://yourlocalhost/admin` using default credentials `admin`/`admin`

You can now fill artoworks by adding them manually or importing using the "spice harvester" (harvests using OAI PMH).

This application uses [IIPImage server](http://iipimage.sourceforge.net/) for zoomable (and downloadable) images.

IIPImage must be seen localy, what can be achieved using a ProxyForwarding.

To enable image server for default oai set (Europeana), put this lines of code into your virtualhost setup:

```
ProxyPass /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi

ProxyPassReverse /fcgi-bin/iipsrv.fcgi http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi
```


## Maintainer

This project is maintained by [lab.SNG](http://lab.sng.sk). If you have any questions please don't hesitate to ask them in an issue or email us at [lab@sng.sk](mailto:lab@sng.sk).


## License

This is free software released into the [public domain](http://unlicense.org/). Please see [License File](LICENSE) for more information.