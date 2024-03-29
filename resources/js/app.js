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

// Vue components
import Vue from 'vue'
import { ZiggyVue } from 'ziggy';
import * as LottiePlayer from "@lottiefiles/lottie-player";
import { VueMasonryPlugin } from 'vue-masonry'
import { Lang } from 'laravel-vue-lang';
import VueClickAway from "vue3-click-away";

window.Vue = Vue
Vue.use(VueMasonryPlugin)
Vue.use(VueClickAway)
Vue.use(Lang, { fallback: 'sk' })
Vue.component('featured-piece-click-tracker', require('./components/FeaturedPieceClickTracker.vue').default);
Vue.component('filter-sort-by', require('./components/filter/SortBy.vue').default);
Vue.component('filter-checkbox', require('./components/filter/Checkbox.vue').default);
Vue.component('filter-custom-select', require('./components/filter/CustomSelect.vue').default);
Vue.component('filter-new-items-controller', require('./components/catalog-new/ItemsFilterController.vue').default);
Vue.component('filter-new-popover.group-controller', require('./components/catalog-new/PopoverGroupController.vue').default);
Vue.component('filter-new-custom-select-popover-label', require('./components/catalog-new/NewCustomSelectPopoverLabel.vue').default);
Vue.component('filter-new-color-slider', require('./components/catalog-new/NewColorSlider.vue').default);
Vue.component('filter-new-year-slider', require('./components/catalog-new/NewYearSlider.vue').default);
Vue.component('filter-new-custom-checkbox', require('./components/catalog-new/NewCustomCheckbox.vue').default);
Vue.component('filter-disclosure-controller', require('./components/catalog-new/DisclosureModalController.vue').default);
Vue.component('toggle-controller', require('./components/ToggleController.vue').default);
Vue.component('reload-controller', require('./components/ReloadController.vue').default);
Vue.component('filter-search-options-controller', require('./components/catalog-new/SearchOptionsController.vue').default);
Vue.component('filter-popover-controller', require('./components/catalog-new/PopoverController.vue').default);
Vue.component('catalog.infinite-scroll', require('./components/catalog-new/InfiniteScroll.vue').default);
Vue.component('catalog.artwork-image-controller', require('./components/catalog-new/ArtworkImageController.vue').default);
Vue.component('catalog.number-formatter', require('./components/catalog-new/NumberFormatter.vue').default);
Vue.component('catalog.author-formatter', require('./components/catalog-new/AuthorFormatter.vue').default);
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
Vue.component('newsletter-signup.form-controller', require('./components/newsletter-signup/FormController.vue').default);
Vue.component('newsletter-signup.bottom-modal-controller', require('./components/newsletter-signup/BottomModalController.vue').default);
Vue.component('slider', require('./components/vue/slider').default);
Vue.component('color-slider', require('./components/vue/color-slider').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$route = route;
new Vue({ el: '#app' });
