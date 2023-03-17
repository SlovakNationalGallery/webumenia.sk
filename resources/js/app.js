require('./bootstrap')
require( 'slick-carousel');
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

// Components
require('./components/searchbar')
require('./components/clipboard-button')
require('./components/newsletter-signup-form-tracker')

// Vue components
import Vue from 'vue'
import 'livewire-vue'
import { Lang } from 'laravel-vue-lang';
import { VueMasonryPlugin } from 'vue-masonry'
Vue.use(VueMasonryPlugin)


window.Vue = Vue
Vue.use(Lang, { fallback: 'sk' })
Vue.component('featured-piece-click-tracker', require('./components/FeaturedPieceClickTracker.vue').default);
Vue.component('filter-sort-by', require('./components/filter/SortBy.vue').default);
Vue.component('filter-checkbox', require('./components/filter/Checkbox.vue').default);
Vue.component('filter-custom-select', require('./components/filter/CustomSelect.vue').default);
Vue.component('filter-new-items-controller', require('./components/catalog-new/ItemsFilterController.vue').default);
Vue.component('filter-new-popover', require('./components/catalog-new/PopoverFilter.vue').default);
Vue.component('filter-new-custom-select-popover-label', require('./components/catalog-new/NewCustomSelectPopoverLabel.vue').default);
Vue.component('filter-new-options', require('./components/catalog-new/Options.vue').default);
Vue.component('filter-new-color-slider', require('./components/catalog-new/NewColorSlider.vue').default);
Vue.component('filter-new-year-slider', require('./components/catalog-new/NewYearSlider.vue').default);
Vue.component('filter-new-custom-checkbox', require('./components/catalog-new/NewCustomCheckbox.vue').default);
Vue.component('filter-new-selected-labels', require('./components/catalog-new/NewSelectedLabels.vue').default);
Vue.component('filter-new-sort', require('./components/catalog-new/NewSort.vue').default);
Vue.component('filter-disclosure-button', require('./components/catalog-new/DisclosureFilterButton.vue').default);
Vue.component('filter-disclosure-list-button', require('./components/catalog-new/DisclosureFilterListButton.vue').default);
Vue.component('filter-disclosure-modal', require('./components/catalog-new/DisclosureFilterModal.vue').default);
Vue.component('filter-disclosure-view', require('./components/catalog-new/DisclosureFilterView.vue').default);
Vue.component('filter-disclosure-controller', require('./components/catalog-new/DisclosureModalController.vue').default);
Vue.component('filter-new-popover-group-controller', require('./components/catalog-new/PopoverGroupController.vue').default);
Vue.component('filter-show-more', require('./components/catalog-new/ShowMore.vue').default);
Vue.component('filter-masonry', require('./components/catalog-new/ArtworksMasonry.vue').default);
Vue.component('filter-artwork-image', require('./components/catalog-new/ArtworkImage.vue').default);
Vue.component('flickity', require('./components/Flickity.vue').default);
Vue.component('home.shuffle-orchestrator', require('./components/home/ShuffleOrchestrator.vue').default);
Vue.component('home.transition-in-place', require('./components/home/TransitionInPlace.vue').default);
Vue.component('tabs-controller', require('./components/TabsController.vue').default);
Vue.component('tab', require('./components/Tab.vue').default);
Vue.component('tab-panel', require('./components/TabPanel.vue').default);
Vue.component('user-collections-link', require('./components/user-collections/Link.vue').default);
Vue.component('user-collections-nav-link', require('./components/user-collections/NavLink.vue').default);
Vue.component('user-collections-favourite-button', require('./components/user-collections/FavouriteButton.vue').default);
Vue.component('user-collections-clear-button', require('./components/user-collections/ClearButton.vue').default);
Vue.component('user-collections-share-form', require('./components/user-collections/ShareForm.vue').default);
Vue.component('user-collections-store', require('./components/user-collections/Store.vue').default);
Vue.component('user-collections-shared-collection', require('./components/user-collections/SharedCollection.vue').default);
Vue.component('color-widget', require('./components/ColorWidget.vue').default);
Vue.component('year-slider', require('./components/YearSlider.vue').default);
Vue.component('inline-input', require('./components/InlineInput.vue').default);
Vue.component('copy-to-clipboard-group', require('./components/CopyToClipboardGroup.vue').default);
Vue.component('copy-to-clipboard-link', require('./components/CopyToClipboardLink.vue').default);
Vue.component('bottom-modal', require('./components/BottomModal.vue').default);
Vue.component('user-interaction-context', require('./components/UserInteractionContext.vue').default);
Vue.component('livewire-vue-adaptor', require('./components/LivewireVueAdaptor.vue').default);
Vue.component('slider', require('./components/vue/slider').default);
Vue.component('color-slider', require('./components/vue/color-slider').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 new Vue({ el: '#app' });
