# Change Log
All notable changes to this project will be documented in this file[^1].

## [Unreleased]
### Fixed
- facebook sharing handling, pinterest media preview

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
