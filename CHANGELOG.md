# Change Log
All notable changes to this project will be documented in this file[^1].

## [Unreleased]
### Fixed
- fix object type translation to on mobile filter
- fix sort function on select options

## [2.88.0] - 2023-01-12
### Changed
- unisharp/laravel-filemanager to version 2.6.4
### Fixed
- failed_jobs table to include the uuid column

## [2.87.1] - 2023-12-21
### Fixed
- v1 Item detail to not throw errors

## [2.87.0] - 2023-12-21
### Fixed
- BottomModal variant of the newsletter signup form
- Zoom viewer for more than 10 related images

## [2.86.0] - 2023-12-07
### Fixed
- indexing of Admin section

## [2.85.0] - 2023-11-23
### Changed
- GMUHK default authority role

### Fixed
- redundant commas from authority details

## [2.84.0] - 2023-11-02
### Changed
- title generators to turn them into controller methods

## [2.83.0] - 2023-10-26
### Fixed
- total counts for /api/v1/items

## [2.82.0] - 2023-10-19
### Changed
- default sort for /api/v1/items

### Fixed
- security issue in @babel/traverse indetified by dependabot

## [2.81.0] - 2023-10-12
### Changed
- deploy actions to deploy to prod from a release

## [2.80.0] - 2023-09-29
### Added
- admin:are you sure popup
- SetExperiments middleware for setting experiment flags from anywhere
- RedirectLegacyCatalogRequest middleware for handling "legacy" catalog requests in new catalog
- 'hidden' catalog fields to V1 API filterables

### Changed
- add soft deletes to articles
- remove pagination from collections and articles in admin
- Change font sizes on article, collection

## [2.79.0] - 2023-08-31
### Added
- images:generate-ratios command

### Changed
- behaviour for /api/v1/items/aggregations so that facet doesn't filter itself
- display published date on collection index
- change the public domain status of artworks with unknown author to free only if made before 1940
## [2.78.0] - 2023-07-21
### Added
- images->deep_zoom_url to /api/v2/items/{id}

## [2.77.0] - 2023-06-09
### Added
- image_ratio field to /api/v2/items/{id}

### Changed
- Authority#has_image to cast to boolean

## [2.76.0] - 2023-05-25
### Added
- authors_formatted field to /api/v1/items

### Changed
- Google Maps API key to be included from env variable

## [2.75.0] - 2023-05-11
### Changed
- Vue version to v3 migration build

### Fixed
- homepage featured author images query

## [2.74.1] - 2023-04-15
### Changed
- spatie/laravel-newsletter to drewm/mailchimp-api

### Fixed
- newsletter subscription form

## [2.74.0] - 2023-04-13
### Added
- additional sortables in /api/v1/items
- spatie/laravel-ignition

### Changed
- upgrade to Laravel 10
- upgrade to PHPUnit 10

### Fixed
- artwork map styling

## [2.73.0] - 2023-03-09
### Added
- multi-term filtering to /api/v1/items
- boolean filtering in /api/v1/items
- color filtering in /api/v1/items

## [2.72.1] - 2023-02-10
### Added
- `disk` property to Imports

## [2.72.0] - 2023-02-10
### Fixed
- imported images count
- proper collection translation of sort collection by name

### Added
- infinite scroll to artworks carousel
- ability to parse collection teasers into articles

### Changed
- Filter controller architecture
- connection to IIP via WebDAV

## [2.71.0] - 2022-12-17
### Added
- date_earliest and date_latest in ItemResource
- import_iip disk

### Fixed
- mapping of item dating in harvester
- prefixing of iipimg_url

## [2.70.0] - 2022-11-08
### Added
- Temporary outage notice
- new catalog "kitchen sink" page (work-in-progress)

## [2.69.0] - 2022-11-08
### Fixed
- indexing of Authority roles

### Changed
- refactored import files

### Added
- parse article teaser from admin

## [2.68.0] - 2022-10-13
### Added
- default Scout config to enable Scout search functionality

### Changed
- display start and end date with roles on authors page

### Fixed
- article category on homepage
- Item::related scope to match catalog search query
- queue config in .env.example

## [2.67.0] - 2022-10-04
### Fixed
- lazy loading items for reindex

### Added
- artwork info to citation note on download
- import iip images command

### Changed
- display start and end date with roles on authors page

## [2.66.0] - 2022-09-22
### Fixed
- item detail view to eliminate N+1 queries

### Changed
- migrate factories to laravel 8 + style factories

## [2.65.0] - 2022-09-12
### Fixed
- database seeders
- empty state of /objednavka page
- orders.purpose column type (hotfix)

### Changed
- author to gender inclusive variant
- database seeders: article and collection
- use entity instead of harvester class in records type
- gender sensitive roles + format affected files

## [2.64.1] - 2022-08-25
### Fixed
- year slider to ignore its values if it was not touched
- authority collection counts
- saving collections
- artwork detail image stretched when in slick

## [2.64.0] - 2022-08-19
### Fixed
- item_images indexes

### Added
- importer role
- import permissions
- clear image functionality for authorities in admin
- filter items by collection in admin
- confirm collection delete

## [2.63.0] - 2022-08-19
### Added
- item sortables

### Changed
- MySQL database to enabled strict mode
- delete title, order from item images
- order item images by iipimg_url

### Fixed
- tests & harvesters to work with MySQL strict mode
- saving item after deletion during harvest

## [2.62.0] - 2022-08-12
### Added
- item filtering by collection_id (in admin)

## [2.61.1] - 2022-08-05
### Fixed
- Saving shuffled items
- items search in admin

## [2.61.0] - 2022-07-29
### Added
- Mass tagging feature in admin

### Changed
- KolekciaController to optimize queries
- Item view count is indexed every 24 hours instead of immediately

### Fixed
- sorting of collection items
- Item admin form to not show 'item.' prefix in choices

## [2.60.0] - 2022-07-22
### Changed
- Laravel version to 9
- PHP to 8.1
- lorisleiva/laravel-deployer to deployer/deployer
- laravelium/sitemap to spatie/laravel-sitemap

## [2.59.1] - 2022-07-22
### Fixed
- Authority link labels to be optional

## [2.59.0] - 2022-07-15
### Changed
- front-page latest content (articles & collections) to display in two rows
- various details for shuffled items

## [2.58.0] - 2022-07-07
### Changed
- reproductions page using Tailwind CSS
- meta description to fit under 160 characters

## [2.57.1] - 2022-07-04
- fix getting published collections

## [2.57.0] - 2022-07-01
### Changed
- narrow down Item detail collections to published

### Fixed
- indexing for published Collections

## [2.56.0] - 2022-06-23
### Changed
- zoom viewer to use custom controls

## [2.55.0] - 2022-06-03
### Added
- front-end components for "shuffle" items

### Changed
- whole featured piece home section to be clickable

## [2.54.0] - 2022-05-19
### Changed
- Removed PNP from downloadable items
- Information page with updated data

## [2.53.0] - 2022-05-13
### Changed
- temporarily removed print reproductions

## [2.52.0] - 2022-05-13
### Changed
- tracking of featured piece clicks from Livewire to Vue
- FeaturedPiece supports rich formatting

## [2.51.2] - 2022-05-11
### Fixed
- constructing importers

## [2.51.1] - 2022-04-26
### Fixed
- item contributor mapping

## [2.51.0] - 2022-04-14
### Added
- back-end for shuffled items

### Fixed
- "high res" items count on home page

## [2.50.0] - 2022-04-07
### Added
- item style period, current location
- authority matching in importers

### Fixed
- newsletter sign-up to include marketing permissions

## [2.49.0] - 2022-03-25
### Changed
- galleries list to come from a config file
- default home page to new home page

## [2.48.0] - 2022-03-24
### Added
- global word-break:keep-all
- "counts blurb" to new homepage
- performance tweaks to new homepage

### Changed
- resolve api route name conflicts

### Fixed
- some styling issues with new Openseadragon
- disabled global Bootstrap `<a>` styles

## [2.47.0] - 2022-03-11
### Added
- "featured piece" front-end component
- "featured artwork" front-end component
- "featured author" front-end component
- Ukrainian flag glow
- OpenAPI preparation

### Changed
- Openseadragon version to 3.0.0

## [2.46.0] - 2022-02-25
### Added
- Featured Artworks back-end section
- new Tailwind component for back-end/admin

### Changed
- font loading (fix for Source Serif Pro)
- Tailwind scale to start from 1rem = 16px

## [2.45.0] - 2022-02-18
### Added
- age group "all" and media type "activities" to edu article tags

### Fixed
- homepage carousel not showing subtitle/excerpt

## [2.44.2] - 2022-02-13
### Fixed
- empty authority names

## [2.44.1] - 2022-02-11
### Fixed
- missing authorities during reindex

## [2.44.0] - 2022-02-01
### Added
- "frameColor" to JIRA request in order process
- use authority matcher in item indexing
- "latest content" section for new homepage
- import multiple images from pnp karasek

### Changed
- delete formerly attributed authors
- do not harvest formerly attributed authors

## [2.43.0] - 2022-02-04
### Added
- basic Tailwind set-up
- Tabs and Flickity Vue components

## [2.42.2] - 2022-01-31
### Fixed
- always fill in purpose in JIRA request in order
- diacritic in frame variants in order form

## [2.42.1] - 2022-01-30
### Fixed
- broken authors page in czech language

## [2.42.0] - 2022-01-28
### Added
- filter catalog by curator and fix harvesting of curator data
- attributes "format", "deliveryPoint" and "note" to JIRA request in order process

### Fixed
- calculation of isFree for artworks with authorities without death_year

## [2.41.0] - 2022-01-14
### Added
- filter by gender to authorities
- authority matching of swapped names
- GMUHK harvest authority matching

### Changed
- inscription length in item_translations table
- GMUHK work types
- Slide to FeaturedPiece
- no-image for alive/dead authors

### Fixed
- gender-specific no-image for authorities

## [2.40.1] - 2022-01-04
### Fixed
- indexing multiple medium values

## [2.40.0] - 2021-12-23
### Added
- catalog object type, medium, has text filters

### Fixed
- hreflang urls generated by url_to
- blurry images in article/collection header
- dots not showing under multi-image artowork detail images

## [2.39.0] - 2021-12-14
### Changed
- reflect GMUHK provider changes

## [2.38.0] - 2021-12-13
### Fixed
- multiple item work/object types

## [2.37.0] - 2021-12-03
### Added
- newsletter sign-up form to info page

### Fixed
- gender sensitive translation strings in author profile
- "alternate" hreflangs not including URL (query) params

## [2.36.0] - 2021-11-26
### Added
- custom redirects
- newsletter signup modal and inline form

### Fixed
- "alternate" hreflangs not referencing each other
- shared collection added to user collections only after create

## [2.35.0] - 2021-11-22
### Changed
- shared user collections to show publicly

### Added
- laravel-vue-lang to use lang files in Vue

## [2.34.0] - 2021-11-12
### Changed
- shared user collections to show in favourites
- newsletter sign-up form widget

## [2.33.0] - 2021-10-29
### Added
- default e-mail template, style and config
- e-mail message for created shared user collections

### Changed
- favourites navbar button to always show

### Fixed
- Vue initialization in admin

## [2.32.1] - 2021-10-14
### Fixed
- public views for shared user collections
- pagination for articles and edu articles

## [2.32.0] - 2021-10-04
### Added
- paging for front page, articles and edu articles
- user collection sharing

### Changed
- Laravel Mix & assets

## [2.31.0] - 2021-09-09
### Added
- Scena.AI snippet

## [2.30.0] - 2021-08-27
### Added
- GMUHK harvester
- item object type attribute
- GMUHK harvest work type

### Fixed
- escape HTML characters in share buttons components

### Changed
- Migrate Slides to spatie/laravel-media-library
- Removed Typeform integration

## [2.29.3] - 2021-06-29
### Added
- Typeform integration

## [2.29.2] - 2021-06-11
### Changed
- Enable Edu section for public (in main nav)

## [2.29.1] - 2021-06-04
### Fixed
- Deployments to test use chmod instead of setfacl

### Changed
- Editor role can edit articles
- Speed up CI tests by using docker build caching

## [2.29.0] - 2021-05-25
### Added
- "Education" section

### Fixed
- Reading time in articles/collections

### Changed
- Unify filters for articles and collections

## [2.28.1] - 2021-05-13
### Fixed
- Harvester error logging
- Harvester missing total in progress

## [2.28.0] - 2021-05-05
### Changed
- Do not show formerly attributed authors in item detail
- Remove docker-compose.test.yml
- Update README
- Remove LinkMapper from AuthorityImporter

### Fixed
- Username not fillable in admin
- Do not overwrite authority links from CEDVU

## [2.27.0] - 2021-04-16
### Fixed
 - Counting clicks on carousel slides

### Changed
- Upgrade to Laravel 7
- Upgrade to Laravel 8

## [2.26.0] - 2021-04-09
### Added
- File uploads management
- KGC into list of galleries in the info page

### Fixed
- Remove authorities
- Sort options in articles filters by count
- Do not overwrite authority biography via OAI-PMH
- Counting clicks on carousel slides

### Changed
- Clean up Enforcer tables
- Move to PHP 7.4

## [2.25.0] - 2021-04-08
### Changed
- upgrade to Laravel 6

## [2.24.0] - 2021-03-26
### Fixed
- separate authority sources by newlines
- updating authority sources

### Changed
- Link (source) URL can be null

## [2.23.1] - 2021-03-22
### Fixed
- unescape description in artwork detail

## [2.23.0] - 2021-03-19
### Fixed
- form for authorities
- nav links not highlighted in non-SK locales
- styling issue for selects on reproduction order form
- skip to artworks in collection
- escape attributes in artwork detail

## [2.22.0] - 2021-03-11
### Added
- add filtering to articles section

### Changed
- carousel & hero image refactoring, added img-srcset
- unified main image storing for slides, collections and articles
- new styling for artwork / article / collection, metadata blocks

## [2.21.2] - 2021-03-10
### Fixed
- browsing between artworks within collection

## [2.21.1] - 2021-03-09
### Fixed
- form for carousel, harvests, imports and users
- zoom

## [2.21.0] - 2021-03-08
### Fixed
- Fix linked-combos links for item relationships
- Fix form for articles and collections
- Fix patternlib page

## [2.20.0] - 2021-02-26
### Added
- admin form and view for 'sources' links

## [2.19.0] - 2021-02-18
### Fixed
- paging issues with user collections
- Enable adding to user collections in collection view

### Changed
- autocomplete for item-author and item-authority-role fiels
- ckeditor allows to add slick
- karasek importer

## [2.18.0] - 2021-02-10
### Added
- karasek items indicator

## [2.17.1] - 2021-01-24
### Fixed
- unescaped data displayed in alt attributes

## [2.17.0] - 2021-01-22
### Added
- add es:migrate command
- slick to ckeditor

### Changed
- Do not specify default (develop) branch for test deploys
- index is_for_reproductions in Elastic
- exclude Cierne diery from reproductions
- Use aliased indexes for es:setup
- Speed up re-index for Items

### Fixed
- Do not load JS for "load-more" on single-page results
- Fix zoom for related work in non-SK locale

## [2.16.0] - 2021-01-15
### Added
- user collections

### Changed
- Make (reproduction) notices editable in admin

## [2.15.1] - 2021-01-08
### Changed
- use debugbar in dev env
- eager load articles
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
