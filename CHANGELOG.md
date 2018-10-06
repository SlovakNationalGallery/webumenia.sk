# Change Log
All notable changes to this project will be documented in this file[^1].

## [Unreleased]
### Fixed
- Duplicate authors with links
- Authorities Search endpoint of admin fixed
- Multiple item images form in admin

### Added
- Remove query string from localized urls
- Use SQLite in tests
- Dockerfiles for WU to run in docker
- Curator of artwork in detail view
- admin-editable cron_status attribute added to SpiceHarvester harvests
- daily and weekly cron jobs scheduled for harvests with appropriate cron_status
- footer component
- newsletter link into footer
- VSG to the list of galleries
- Refactor spice harvester
- Show harvest progress in admin
- Symfony forms

### Changed
- allow download of public-domain artworks from VSG
- making map with galleries interactive in info section
- made models Item, Authority, Article and Collection translatable
- separate elastic index for each locale
- footer social icons

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
- ZoomController

### Changed
- Info section to include MG on map + update current lab.SNG team
- Made ZoomViewer component embedabble in static HTML via editor

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
