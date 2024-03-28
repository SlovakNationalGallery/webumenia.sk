import { createApp, configureCompat } from 'vue'
import { Lang } from 'laravel-vue-lang'

import 'vue-select/dist/vue-select.css'

configureCompat({
    RENDER_FUNCTION: false,
    INSTANCE_SCOPED_SLOTS: false,
    TRANSITION_GROUP_ROOT: false,
    INSTANCE_LISTENERS: false,
    INSTANCE_ATTRS_CLASS_STYLE: false,
    ATTR_ENUMERATED_COERCION: false,
    WATCH_ARRAY: false,
})

createApp()
    .use(Lang, { fallback: 'sk' })

    .component('admin-links-input', require('./components/admin/LinksInput.vue').default)
    .component('autocomplete', require('./components/Autocomplete.vue').default)
    .component('linked-combos', require('./components/vue/linked-combos').default)
    .component('query-string', require('./components/QueryString.vue').default)
    .component('croppr', require('./components/Croppr.vue').default)
    .component('tabs-controller', require('./components/TabsController.vue').default)
    .component('tab', require('./components/Tab.vue').default)
    .component('tab-panel', require('./components/TabPanel.vue').default)
    .component(
        'admin.shuffle-item-filters',
        require('./components/admin/ShuffleItemFilters.vue').default
    )

    .mount('#wrapper')
