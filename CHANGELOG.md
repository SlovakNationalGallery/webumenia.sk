# Change Log
All notable changes to this project will be documented in this file[^1].

## [Unreleased]
### Fixed
- OAI download command now no longer skips half the downloads
- error message added showing if and how many downloads fail
- Display localized section titles in autocomplete
- missing article category in autocomplete suggestions
- exception in autocomplete if article image is missing
- multiple item images handling

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
[1.4.3]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/70
[1.4.2]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/69
[1.4.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/67
[1.3.0]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/52
[1.2.6]: https://github.com/SlovakNationalGallery/web-umenia-2/pull/31


[^1]: The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).
