# Change Log
All notable changes to this project will be documented in this file[^1].

## [Unreleased]
## [2.15.1] - 2021-01-08
### Changed
- use debugbar in dev env
- eager load articles
- Make (reproduction) notices editable in admin
- Bump ini from 1.3.5 to 1.3.7

## [2.15.0] - 2020-12-18
### Added
- save item image ratio
- karasek importer

## [2.14.0] - 2020-12-14
### Added
- user collections

## [2.15.1] - 2021-01-08
### Changed
- use debugbar in dev env
- eager load articles
- Make (reproduction) notices editable in admin
- Bump ini from 1.3.5 to 1.3.7

## [2.15.0] - 2020-12-18
### Added
- save item image ratio
- karasek importer

## [2.14.0] - 2020-12-14
### Added
- authority matcher

### Fixed
- Do not trigger an Elasticsearch error on high page numbers

### Changed
- connect artwork author to authorities
- Bump up sentry/sentry-laravel to 2.3
- use authority matcher in item detail
- SGP into list of galleries in the info page
- Run tests on GitHub Actions

## [2.13.1] - 2020-12-07
### Fixed
- Use method asset_timed to avoid caching of og:image

## [2.13.0] - 2020-11-27
### Added
- Add 'copy citation' button to artwork detail

### Changed
- Bump up Bootstrap to 3.4.1 and bump down jQuery to 3.4.1

## [2.12.0] - 2020-11-26
### Changed
- Use laravel-mix for (some) JavaScript files
- Add item (artwork) data to GTM dataLayer
- Use atymic/deployer-php-action@2.0 for auto-deploys
- Update resolve-url-loader to 3.1.2

### Fixed
- Restart queue workers on deploy

## [2.11.1] - 2020-11-11
### Changed
- revert escape html characters in PNP trienale importer

## [2.11.0] - 2020-11-10
### Added
- automatically deploy "develop" branch to test
- add missing fields to elasticsearch for exlibris 2020

### Changed
- skip links in flysystem
- change elasticsearch client config
- run tests on MySQL 5.7
- escape html characters in PNP trienale importer

## [2.10.0] - 2020-11-02
### Added
- PNP trienale importer

### Fixed
- wrong method name in harvester

### Changed
- alert in reproductions section

## [2.9.0] - 2020-10-27
### Added
- Google Tag Manager (GTM) setup

## [2.8.0] - 2020-10-26
### Added
- color picker to patternlib
- item work type tree structure
- organization authority detail
- update harvest model status on harvesting failed

### Fixed
- authority year range filter
- deletion of item images on harvest
- google maps api call
- item related work parsing and displaying
- slow query on detail page
- do not update empty pivot data in harvester
- wrong abort function call

### Changed
- alert in reproductions section

## [2.7.4] - 2020-08-25
### Changed
- text in reproductions section

## [2.7.3] - 2020-07-02
### Fixed
- link to zoom on preview image in artwork detail
- keep carousel for multiple preview images from IIP

## [2.7.2] - 2020-07-02
### Changed
- COVID-19 alert for printed reproductions
- temporarily disable loading preview image from IIP in artwork detail

## [2.7.1] - 2020-05-28
### Fixed
- mapping of item identifier in harvester

## [2.7.0] - 2020-05-17
### Fixed
- preview main / header image within article and collection admin editor
- prevent storing of empty alternative locales for articles / collections
- small locale fixes
- absolute urls for og:image
- open importing csv files with read permissions only
- ordering of catalogue by the view count
- search of unlisted place in authority filter
- missing medium, related work item filters
- overwriting authority's attributes in item harvester
- indexing of item's work types

### Changed
- COVID-19 alert for printed reproductions
- align and layout in order section

## [2.6.3] - 2020-05-12
### Fixed
- creating order with empty address

## [2.6.2] - 2020-05-11
### Fixed
- creating order with empty note

## [2.6.1] - 2020-05-04
### Fixed
- link on preview image in artwork detail without zoom

### Changed
- update lazysizes from 4.1.4 to 5.2.0

## [2.6.0] - 2020-05-01
### Added
- enable random ordering within catalogue
- Laravel Mix config for compiling assets

### Fixed
- sort collections in admin by creation date and allow to unpublish them
- corrected few czech localization strings
- article social sharing images
- fix ratio-box padding for responsive images
- unmap item description in harvester

## [2.5.0] - 2020-04-15
### Added
- GMU v Roudnici nad Labem to info page

### Changed
- collection published by date, updated filter and ordering
- disallow importing of relations to non-existing entities

### Fixed
- instantiation of importer in controller
- duplicated authors in item detail

## [2.4.1] - 2020-04-09
### Fixed
- add panorama image dir to deployer config

## [2.4.0] - 2020-04-01
### Added
- attribute "reproductionType" to JIRA request in order process
- collection filter by user / type
- Item credit attribute
- harvest item's authority role

### Fixed
- facebook sharing handling, pinterest media preview
- year-range filter inputs
- trim item's array attribute values

### Changed
- enable translations in harvests
- placement of share buttons within article / author / collection
- bump symfony/http-foundation from 4.4.5 to 4.4.7

## [2.3.0] - 2020-03-20
### Added
- facebook sharing handling, pinterest media preview
- GMU v Roudnici nad Labem importer
- Laravel Deployer config

### Changed
- (Child) sitemaps are now stored in /public/sitemaps

## [2.2.1] - 2020-03-16
### Fixed
- swaped cs/sk translation strings in order

## [2.2.0] - 2020-03-15
### Added
- COVID-19 alert for printed reproductions
- laravel/tinker

### Fixed
- facebook sharing handling, pinterest media preview
- preview image in artwork detail for firefox
- order form translations
- number of involved galleries on frontpage

## [2.1.0] - 2020-03-01
### Fixed
- sort order when order by oldest
- combine search with filters
- zero-results placeholders in filter
- small item images are displayed incorrectly
- authority search in admin

### Changed
- limit for similiar items / similiar by color items
- bump symfony/mime from 4.3.4 to 4.4.4
- bump symfony/http-foundation from 4.3.4 to 4.4.5
- social share buttons on artwork / author / collection / article pages

## [2.0.1] - 2020-02-21
### Fixed
- allow unknown authors in filter

## [2.0.0] - 2020-02-13
### Added
- model relationship events
- test via docker in travis
- optional docker build with xdebug
- domain fallback translator
- authority/item title generators
- basic frontend view tests

### Changed
- upgrade to Elasticsearch 7.3
- upgrade laravel translatable models package
- rewrite and remove bouncy package usages
- refactor authority/item filters to use symfony forms
- range color search
- update abandoned composer packages
- moved Elasticsearch Dockerfile to separate repo (WEBUMENIA-1262)

### Fixed
- don't show artworks from the same "related_work" in similiar items by attributes
- relationships listed without values in artist detail
- removing items from collection

## [1.12.0] - 2020-01-23
### Added
- TGP, PGU and PNP into list of galleries in the info page

### Fixed
- error 500 from /dielo/{id}/stiahnut for non-existent IDs (WEBUMENIA-1235)
- 'show more' button in catalog (WEBUMENIA-1219)
- iframe handling in Admin editor
- artwork-detail: prevent upsizing of preview image
- don't display carousel with related works when there are no related works
- missing attributes in artwork detail
- spice harvester process detail view

### Changed
- lab.sng team in info page

## [1.11.0] - 2020-01-17
### Changed
- upgrade to Laravel 5.8

## [1.10.2] - 2020-01-13
### Fixed
- artwork carousel focus - display no border
- allow negative number input for year-range filter
- remove duplicated properties at artwork page

### Changed
- use desaturated color at color picker by default (80% saturation used)
- use one years step within year-range component (before it was 5 )

## [1.10.1] - 2019-12-08
### Added
- alert for christmas order

## [1.10.0] - 2019-12-01
### Added
- color picker in the catalog
- override docker php container for tests
- ImageCarousel Component
- harvesting of failed records

### Fixed
- correct authority zero-value years to null
- filter out unexisting OAI-PMH records on refreshing selected

### Changed
- ArtworkDetailPage shows multiple images (via IIIF from image server) in ImageCarousel
- serve item images via custom image server
- Log exception and continue harvesting
- ArtworkDetailPage layout

## [1.9.1] - 2019-07-30
### Changed
- updated GMaps.js v0.4.13 -> v0.4.25

### Fixed
- redirect to link after click in carousel in landing page
- Google Maps API Key


## [1.9.0] - 2019-07-27
### Added
- error message to harvest data
- map all datings in item harvester
- do not delete harvested items on harvest deletion
- improve reproduction images and enabled ReproductionsPage in NavBar

### Fixed
- Extend memory limit on composer run via docker
- suggest filename for resized item images
- Improved reproduction images and enabled ReproductionsPage in NavBar
- refactor Item::isFree method
- missing item description_source translated attribute
- sort collections by name on frontend
- index all even unexisting translations
- windows issues with docker (missing git, use github https, map ES data dir)
- add link to home page into haburger menu
- harvesters fail on zero results

### Changed
- move description_source_link to translated attributes

## [1.8.1] - 2019-05-04
### Fixed
- infinite recursion on Item/Authority index

## [1.8.0] - 2019-03-12
### Fixed
- Item::isFree conditions priority
- Item images form in admin
- missing relations between items and authorities in harvest
- syncing relations in harvest
- failing harvests
- scheduled harvest jobs
- harvest only alternative authority names
- duplicate authors in item detail
- hostname resolution in guzzle

### Added
- style compilation documentation to readme
- restrict bots from color searching
- describe item's colors on primary image change
- new sorting option (by date - from oldest) in catalog
- PNP importer

### Changed
- removed name from authority_item table

## [1.7.2] - 2019-02-27
### Added
- form to add tags in artwork detail for authorized users

### Fixed
- normalizing tags

## [1.7.1] - 2019-02-13
### Changed
- save new tags lowercased instead of titlecased

## [1.7.0] - 2019-02-07
### Fixed
- Harvesting items without rights
- Duplicated years in item dating formated
- Creating new Artwork
- Editing Artwork without iipimg_url

### Added
- /reprodukcie route
- ReproOffer component
- Headings documented in PatternLib
- Remove query string from localized urls
- Option to launch harvest only for specific record ids
- Switch database / elasticsearch index when working with docker-compose
- footnotes in wysiwyg editor in admin
- Sentry integration
- option to choose frame color in order form

### Changed
- CKEditor version
- JSON response in FileuploaderController@upload
- Move img_url from item_images to items table
- Delete null item images

### Removed
- Printed reproduction options for items without IIP/zoom

## [1.6.2] - 2019-01-16
### Fixed
- search autocomplete for Collections and Articles
- division by zero exception for corrupted images
- indexing translated work_type into ElasticSearch

### Added
- 30 days cache to item image headers


## [1.6.1] - 2018-12-06
### Added
- temporary Christmas alert into order

### Changed
- thank you text after order (with "check your spam folder")

## [1.6.0] - 2018-11-28
### Fixed
- Duplicate authors with links
- Authorities Search endpoint of admin fixed
- Multiple item images form in admin

### Added
- Use SQLite in tests
- Dockerfiles for WU to run in docker
- Curator of artwork in detail view
- admin-editable cron_status attribute added to SpiceHarvester harvests
- daily and weekly cron jobs scheduled for harvests with appropriate cron_status
- footer component
- newsletter link into footer
- responsive image component
- responsive image support with lazyloading for carousel(s) and artwork detail
- VSG to the list of galleries
- Refactor spice harvester
- Show harvest progress in admin
- Symfony forms
- ZoomController


### Changed
- allow download of public-domain artworks from VSG
- making map with galleries interactive in info section
- made models Item, Authority, Article and Collection translatable
- separate elastic index for each locale
- footer social icons
- Made ZoomViewer component embedabble in static HTML via editor

### Removed
- query string from localized urls

## [1.5.3] - 2018-08-14
### Fixed
- OAI download images command reflects changes to Item model

## [1.5.2] - 2018-07-27
### Fixed
- Filter color described items in similar by color

## [1.5.1] - 2018-07-26
### Fixed
- Allow Z-prefixed item ID's in spice harvester

## [1.5.0] - 2018-07-04
### Fixed
- ItemImage model changed to properly deal with fields with unique constraints
- arguments parsed by SpiceHarvester changed to account for new ItemImage table
- OAI download command now no longer skips half the downloads
- error message added showing if and how many downloads fail
- Display localized section titles in autocomplete
- missing article category in autocomplete suggestions
- exception in autocomplete if article image is missing
- multiple item images handling
- back button in artwork zoom

### Added
- Scheduled task to run sitemap creation
- CSV import in admin
- Searchbar component
- [Pull Request Template](.github/pull_request_template.md)

### Changed
- Info section to include MG on map + update current lab.SNG team

## [1.4.3] - 2018-05-25
### Fixed
- removed left-over IIP image URL form field

## [1.4.2] - 2018-05-25
### Fixed
- make reproduction order GDPR compliant

## [1.4.1] - 2018-05-22
### Fixed
- fix download functionality after item_images migration

## [1.4.0] - 2018-05-07
### Added
- Color-list component on artwork detail
- Color description
- LESS to CSS compilation
- ColorPicker component
- Zoom-viewer component
- Multiple images per artwork
- Moravian gallery importer

### Changed
- Favicons & Open Graph tags include components
- Positioned image reference strip on right side of zoom-viewer
- Zoom view showing multiple images

## [1.3.2] - 2018-04-23
### Fixed
- enable ArtworkCarousel to be added to article text

## [1.3.1] - 2018-03-09
### Fixed
- OAI-PMH harvester for translated attributes

## [1.3.0] - 2018-02-20
### Added
- Importer module
- Czech language translations
- Pattern Library at /patternlib
- Tests setup
- Option to run all importers
- Route for requesting resized images at /dielo/nahlad/{id}/{width}
- Lazy loading images in catalog and collection
- Responsive image support in catalog and collection

### Changed
- Enabled reproduction orders with mounting / framing
- Updated isotope-layout
- Link to Public domain statement on CC website

### Fixed
- Prevent upsize when uploading item image
- Featured-article image container width bug
- Two columns bug in iPhone6/SE
- Artwork detail in iOS

## [1.2.9] - 2017-12-05
### Changed
- Enabled reproduction orders with mounting / framing
- Componentized Article thumbnail

### Fixed
- Database seeders

## [1.2.8] - 2017-10-24
### Added
- CONTRIBUTING.md
- CODE_OF_CONDUCT.md

## [1.2.7] - 2017-10-13
### Changed
- License info to MIT in README
- Typos in README

## [1.2.6] - 2017-10-11
### Added
- Welcome section to README
- CHANGELOG

### Changed
- LICENSE from Public Domain into MIT

[Unreleased]: https://github.com/SlovakNationalGallery/web-umenia-2/compare/master...develop
[2.7.0]: https://github.com/SlovakNationalGallery/webumenia.sk/pull/328
[2.6.3]: https://github.com/SlovakNationalGallery/webumenia.sk/pull/321
[2.6.2]: https://github.com/SlovakNationalGallery/webumenia.sk/pull/319
[2.6.1]: https://github.com/SlovakNationalGallery/webumenia.sk/pull/314
[2.6.0]: https://github.com/SlovakNationalGallery/webumenia.sk/pull/313
[2.5.0]: https://github.com/SlovakNationalGallery/webumenia.sk/pull/305
[1.9.1]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/226
[1.9.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/223
[1.8.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/191
[1.7.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/169
[1.6.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/108
[1.5.3]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/97
[1.5.2]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/94
[1.5.1]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/93
[1.5.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/80
[1.4.3]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/70
[1.4.2]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/69
[1.4.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/67
[1.3.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/52
[1.2.6]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/31


[^1]: The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).
