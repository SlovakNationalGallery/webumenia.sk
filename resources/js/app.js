require('./bootstrap')
require('slick-carousel')
require('selectize')

const jQueryBridget = require('jquery-bridget')
const Isotope = require('isotope-layout')
const InfiniteScroll = require('infinite-scroll')

jQueryBridget('isotope', Isotope, $)
jQueryBridget('infiniteScroll', InfiniteScroll, $)
InfiniteScroll.imagesLoaded = require('imagesloaded')

require('lazysizes')
require('lazysizes/plugins/unveilhooks/ls.unveilhooks')
require('lazysizes/plugins/respimg/ls.respimg')
require('jquery.easing')
require('@lottiefiles/lottie-player')

// Components
require('./components/searchbar')
require('./components/clipboard-button')

// Vue
import { createApp, configureCompat } from 'vue'
import { VueMasonryPlugin } from 'vue-masonry'
import VueClickAway from 'vue3-click-away'
import { Lang } from 'laravel-vue-lang'
import { ZiggyVue } from 'ziggy'

configureCompat({
    RENDER_FUNCTION: false,
    INSTANCE_SCOPED_SLOTS: false,
})

createApp({
    // TODO remove after upgrade to Vue3
    compilerOptions: {
        whitespace: 'preserve',
    },
})
    .use(VueMasonryPlugin)
    .use(VueClickAway)
    .use(Lang, { fallback: 'sk' })
    .use(ZiggyVue)

    .component(
        'featured-piece-click-tracker',
        require('./components/FeaturedPieceClickTracker.vue').default
    )
    .component('filter-sort-by', require('./components/filter/SortBy.vue').default)
    .component('filter-checkbox', require('./components/filter/Checkbox.vue').default)
    .component('filter-custom-select', require('./components/filter/CustomSelect.vue').default)
    .component(
        'filter-new-items-controller',
        require('./components/catalog-new/ItemsFilterController.vue').default
    )
    .component(
        'filter-new-popover.group-controller',
        require('./components/catalog-new/PopoverGroupController.vue').default
    )
    .component(
        'filter-new-custom-select-popover-label',
        require('./components/catalog-new/NewCustomSelectPopoverLabel.vue').default
    )
    .component(
        'filter-new-color-slider',
        require('./components/catalog-new/NewColorSlider.vue').default
    )
    .component(
        'filter-new-year-slider',
        require('./components/catalog-new/NewYearSlider.vue').default
    )
    .component(
        'filter-new-custom-checkbox',
        require('./components/catalog-new/NewCustomCheckbox.vue').default
    )
    .component(
        'filter-disclosure-controller',
        require('./components/catalog-new/DisclosureModalController.vue').default
    )
    .component('toggle-controller', require('./components/ToggleController.vue').default)
    .component('reload-controller', require('./components/ReloadController.vue').default)
    .component(
        'filter-search-options-controller',
        require('./components/catalog-new/SearchOptionsController.vue').default
    )
    .component(
        'filter-popover-controller',
        require('./components/catalog-new/PopoverController.vue').default
    )
    .component(
        'catalog.infinite-scroll',
        require('./components/catalog-new/InfiniteScroll.vue').default
    )
    .component(
        'catalog.artwork-image-controller',
        require('./components/catalog-new/ArtworkImageController.vue').default
    )
    .component(
        'catalog.number-formatter',
        require('./components/catalog-new/NumberFormatter.vue').default
    )
    .component(
        'catalog.author-formatter',
        require('./components/catalog-new/AuthorFormatter.vue').default
    )
    .component('flickity', require('./components/Flickity.vue').default)
    .component(
        'home.shuffle-orchestrator',
        require('./components/home/ShuffleOrchestrator.vue').default
    )
    .component(
        'home.transition-in-place',
        require('./components/home/TransitionInPlace.vue').default
    )
    .component('tabs-controller', require('./components/TabsController.vue').default)
    .component('tab', require('./components/Tab.vue').default)
    .component('tab-panel', require('./components/TabPanel.vue').default)
    .component('user-collections-link', require('./components/user-collections/Link.vue').default)
    .component(
        'user-collections-nav-link',
        require('./components/user-collections/NavLink.vue').default
    )
    .component(
        'user-collections-favourite-button',
        require('./components/user-collections/FavouriteButton.vue').default
    )
    .component(
        'user-collections-clear-button',
        require('./components/user-collections/ClearButton.vue').default
    )
    .component(
        'user-collections-share-form',
        require('./components/user-collections/ShareForm.vue').default
    )
    .component('user-collections-store', require('./components/user-collections/Store.vue').default)
    .component(
        'user-collections-shared-collection',
        require('./components/user-collections/SharedCollection.vue').default
    )
    .component('color-widget', require('./components/ColorWidget.vue').default)
    .component('year-slider', require('./components/YearSlider.vue').default)
    .component('inline-input', require('./components/InlineInput.vue').default)
    .component('copy-to-clipboard-group', require('./components/CopyToClipboardGroup.vue').default)
    .component('copy-to-clipboard-link', require('./components/CopyToClipboardLink.vue').default)
    .component('bottom-modal', require('./components/BottomModal.vue').default)
    .component(
        'user-interaction-context',
        require('./components/UserInteractionContext.vue').default
    )
    .component(
        'newsletter-signup.form-controller',
        require('./components/newsletter-signup/FormController.vue').default
    )
    .component(
        'newsletter-signup.bottom-modal-controller',
        require('./components/newsletter-signup/BottomModalController.vue').default
    )
    .component('slider', require('./components/vue/slider').default)
    .component('color-slider', require('./components/vue/color-slider').default)

    .mount('#app')
