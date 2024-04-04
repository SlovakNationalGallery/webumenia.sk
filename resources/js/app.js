import './bootstrap'

import 'slick-carousel'
import 'selectize'

import jQueryBridget from 'jquery-bridget'
import Isotope from 'isotope-layout'
import InfiniteScroll from 'infinite-scroll'
import imagesLoaded from 'imagesloaded'

jQueryBridget('isotope', Isotope, $)
jQueryBridget('infiniteScroll', InfiniteScroll, $)
InfiniteScroll.imagesLoaded = imagesLoaded

// require('lazysizes')
// require('lazysizes/plugins/unveilhooks/ls.unveilhooks')
// require('lazysizes/plugins/respimg/ls.respimg')
// require('jquery.easing')
// require('@lottiefiles/lottie-player')

// // Components
// require('./components/searchbar')
// require('./components/clipboard-button')

// // Vue
import { createApp } from 'vue'
import { VueMasonryPlugin } from 'vue-masonry'
import VueClickAway from 'vue3-click-away'
import { i18nVue } from 'laravel-vue-i18n'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.es'

import FeaturedPieceClickTracker from './components/FeaturedPieceClickTracker.vue'
import FilterSortBy from './components/filter/SortBy.vue'
import FilterCheckbox from './components/filter/Checkbox.vue'
import FilterCustomSelect from './components/filter/CustomSelect.vue'
import CatalogNewItemsFilterController from './components/catalog-new/ItemsFilterController.vue'
import CatalogNewPopoverGroupController from './components/catalog-new/PopoverGroupController.vue'
import CatalogNewNewCustomSelectPopoverLabel from './components/catalog-new/NewCustomSelectPopoverLabel.vue'
import CatalogNewNewColorSlider from './components/catalog-new/NewColorSlider.vue'
import CatalogNewNewYearSlider from './components/catalog-new/NewYearSlider.vue'
import CatalogNewNewCustomCheckbox from './components/catalog-new/NewCustomCheckbox.vue'
import CatalogNewDisclosureModalController from './components/catalog-new/DisclosureModalController.vue'
import CatalogNewSearchOptionsController from './components/catalog-new/SearchOptionsController.vue'
import CatalogNewPopoverController from './components/catalog-new/PopoverController.vue'
import CatalogNewInfiniteScroll from './components/catalog-new/InfiniteScroll.vue'
import CatalogNewArtworkImageController from './components/catalog-new/ArtworkImageController.vue'
import CatalogNewNumberFormatter from './components/catalog-new/NumberFormatter.vue'
import CatalogNewAuthorFormatter from './components/catalog-new/AuthorFormatter.vue'
import Flickity from './components/Flickity.vue'
import HomeShuffleOrchestrator from './components/home/ShuffleOrchestrator.vue'
import HomeTransitionInPlace from './components/home/TransitionInPlace.vue'
import ToggleController from './components/ToggleController.vue'
import ReloadController from './components/ReloadController.vue'
import TabsController from './components/TabsController.vue'
import Tab from './components/Tab.vue'
import TabPanel from './components/TabPanel.vue'
import UserCollectionsLink from './components/user-collections/Link.vue'
import UserCollectionsNavLink from './components/user-collections/NavLink.vue'
import UserCollectionsFavouriteButton from './components/user-collections/FavouriteButton.vue'
import UserCollectionsClearButton from './components/user-collections/ClearButton.vue'
import UserCollectionsShareForm from './components/user-collections/ShareForm.vue'
import UserCollectionsStore from './components/user-collections/Store.vue'
import UserCollectionsSharedCollection from './components/user-collections/SharedCollection.vue'
import ColorWidget from './components/ColorWidget.vue'
import YearSlider from './components/YearSlider.vue'
import InlineInput from './components/InlineInput.vue'
import CopyToClipboardGroup from './components/CopyToClipboardGroup.vue'
import CopyToClipboardLink from './components/CopyToClipboardLink.vue'
import BottomModal from './components/BottomModal.vue'
import NewsletterSignupFormController from './components/newsletter-signup/FormController.vue'
import NewsletterSignupBottomModalController from './components/newsletter-signup/BottomModalController.vue'

createApp({
    compilerOptions: {
        isCustomElement: (tag) => tag === 'lottie-player',
    },
})
    .use(VueMasonryPlugin)
    .use(VueClickAway)
    .use(i18nVue, {
        resolve: async (lang) => {
            const langs = import.meta.glob('../../lang/*.json')
            return await langs[`../../lang/${lang}.json`]()
        },
    })
    .use(ZiggyVue)

    .component('featured-piece-click-tracker', FeaturedPieceClickTracker)
    .component('filter-sort-by', FilterSortBy)
    .component('filter-checkbox', FilterCheckbox)
    .component('filter-custom-select', FilterCustomSelect)
    .component('filter-new-items-controller', CatalogNewItemsFilterController)
    .component('filter-new-popover.group-controller', CatalogNewPopoverGroupController)
    .component('filter-new-custom-select-popover-label', CatalogNewNewCustomSelectPopoverLabel)
    .component('filter-new-color-slider', CatalogNewNewColorSlider)
    .component('filter-new-year-slider', CatalogNewNewYearSlider)
    .component('filter-new-custom-checkbox', CatalogNewNewCustomCheckbox)
    .component('filter-disclosure-controller', CatalogNewDisclosureModalController)
    .component('toggle-controller', ToggleController)
    .component('reload-controller', ReloadController)
    .component('filter-search-options-controller', CatalogNewSearchOptionsController)
    .component('filter-popover-controller', CatalogNewPopoverController)
    .component('catalog.infinite-scroll', CatalogNewInfiniteScroll)
    .component('catalog.artwork-image-controller', CatalogNewArtworkImageController)
    .component('catalog.number-formatter', CatalogNewNumberFormatter)
    .component('catalog.author-formatter', CatalogNewAuthorFormatter)
    .component('flickity', Flickity)
    .component('home.shuffle-orchestrator', HomeShuffleOrchestrator)
    .component('home.transition-in-place', HomeTransitionInPlace)
    .component('tabs-controller', TabsController)
    .component('tab', Tab)
    .component('tab-panel', TabPanel)
    .component('user-collections-link', UserCollectionsLink)
    .component('user-collections-nav-link', UserCollectionsNavLink)
    .component('user-collections-favourite-button', UserCollectionsFavouriteButton)
    .component('user-collections-clear-button', UserCollectionsClearButton)
    .component('user-collections-share-form', UserCollectionsShareForm)
    .component('user-collections-store', UserCollectionsStore)
    .component('user-collections-shared-collection', UserCollectionsSharedCollection)
    .component('color-widget', ColorWidget)
    .component('year-slider', YearSlider)
    .component('inline-input', InlineInput)
    .component('copy-to-clipboard-group', CopyToClipboardGroup)
    .component('copy-to-clipboard-link', CopyToClipboardLink)
    .component('bottom-modal', BottomModal)
    .component('newsletter-signup.form-controller', NewsletterSignupFormController)
    .component('newsletter-signup.bottom-modal-controller', NewsletterSignupBottomModalController)

    .mount('#app')
