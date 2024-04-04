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

console.log('Hello from app.js!', $)

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
import { Lang } from 'laravel-vue-lang'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.es'

import FeaturedPieceClickTracker from './components/FeaturedPieceClickTracker.vue'
import Flickity from './components/Flickity.vue'
import HomeShuffleOrchestrator from './components/home/ShuffleOrchestrator.vue'
import HomeTransitionInPlace from './components/home/TransitionInPlace.vue'
import UserCollectionsNavLink from './components/user-collections/NavLink.vue'

createApp({
    compilerOptions: {
        isCustomElement: (tag) => tag === 'lottie-player',
    },
})
    .use(VueMasonryPlugin)
    .use(VueClickAway)
    // .use(Lang, { fallback: 'sk' }) // TODO
    .use(ZiggyVue)

    .component('featured-piece-click-tracker', FeaturedPieceClickTracker)
    //     .component('filter-sort-by', require('./components/filter/SortBy.vue').default)
    //     .component('filter-checkbox', require('./components/filter/Checkbox.vue').default)
    //     .component('filter-custom-select', require('./components/filter/CustomSelect.vue').default)
    //     .component(
    //         'filter-new-items-controller',
    //         require('./components/catalog-new/ItemsFilterController.vue').default
    //     )
    //     .component(
    //         'filter-new-popover.group-controller',
    //         require('./components/catalog-new/PopoverGroupController.vue').default
    //     )
    //     .component(
    //         'filter-new-custom-select-popover-label',
    //         require('./components/catalog-new/NewCustomSelectPopoverLabel.vue').default
    //     )
    //     .component(
    //         'filter-new-color-slider',
    //         require('./components/catalog-new/NewColorSlider.vue').default
    //     )
    //     .component(
    //         'filter-new-year-slider',
    //         require('./components/catalog-new/NewYearSlider.vue').default
    //     )
    //     .component(
    //         'filter-new-custom-checkbox',
    //         require('./components/catalog-new/NewCustomCheckbox.vue').default
    //     )
    //     .component(
    //         'filter-disclosure-controller',
    //         require('./components/catalog-new/DisclosureModalController.vue').default
    //     )
    //     .component('toggle-controller', require('./components/ToggleController.vue').default)
    //     .component('reload-controller', require('./components/ReloadController.vue').default)
    //     .component(
    //         'filter-search-options-controller',
    //         require('./components/catalog-new/SearchOptionsController.vue').default
    //     )
    //     .component(
    //         'filter-popover-controller',
    //         require('./components/catalog-new/PopoverController.vue').default
    //     )
    //     .component(
    //         'catalog.infinite-scroll',
    //         require('./components/catalog-new/InfiniteScroll.vue').default
    //     )
    //     .component(
    //         'catalog.artwork-image-controller',
    //         require('./components/catalog-new/ArtworkImageController.vue').default
    //     )
    //     .component(
    //         'catalog.number-formatter',
    //         require('./components/catalog-new/NumberFormatter.vue').default
    //     )
    //     .component(
    //         'catalog.author-formatter',
    //         require('./components/catalog-new/AuthorFormatter.vue').default
    //     )
    .component('flickity', Flickity)
    .component('home.shuffle-orchestrator', HomeShuffleOrchestrator)
    .component('home.transition-in-place', HomeTransitionInPlace)
    //     .component('tabs-controller', require('./components/TabsController.vue').default)
    //     .component('tab', require('./components/Tab.vue').default)
    //     .component('tab-panel', require('./components/TabPanel.vue').default)
    //     .component('user-collections-link', require('./components/user-collections/Link.vue').default)
    .component('user-collections-nav-link', UserCollectionsNavLink)
    //     .component(
    //         'user-collections-favourite-button',
    //         require('./components/user-collections/FavouriteButton.vue').default
    //     )
    //     .component(
    //         'user-collections-clear-button',
    //         require('./components/user-collections/ClearButton.vue').default
    //     )
    //     .component(
    //         'user-collections-share-form',
    //         require('./components/user-collections/ShareForm.vue').default
    //     )
    //     .component('user-collections-store', require('./components/user-collections/Store.vue').default)
    //     .component(
    //         'user-collections-shared-collection',
    //         require('./components/user-collections/SharedCollection.vue').default
    //     )
    //     .component('color-widget', require('./components/ColorWidget.vue').default)
    //     .component('year-slider', require('./components/YearSlider.vue').default)
    //     .component('inline-input', require('./components/InlineInput.vue').default)
    //     .component('copy-to-clipboard-group', require('./components/CopyToClipboardGroup.vue').default)
    //     .component('copy-to-clipboard-link', require('./components/CopyToClipboardLink.vue').default)
    //     .component('bottom-modal', require('./components/BottomModal.vue').default)
    //     .component(
    //         'user-interaction-context',
    //         require('./components/UserInteractionContext.vue').default
    //     )
    //     .component(
    //         'newsletter-signup.form-controller',
    //         require('./components/newsletter-signup/FormController.vue').default
    //     )
    //     .component(
    //         'newsletter-signup.bottom-modal-controller',
    //         require('./components/newsletter-signup/BottomModalController.vue').default
    //     )

    .mount('#app')
