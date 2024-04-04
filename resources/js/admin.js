import 'vue-select/dist/vue-select.css'

import { createApp } from 'vue'
import { i18nVue } from 'laravel-vue-i18n'

import 'vue-select/dist/vue-select.css'

createApp()
    .use(i18nVue, {
        resolve: async (lang) => {
            const langs = import.meta.glob('../../lang/*.json')
            return await langs[`../../lang/${lang}.json`]()
        },
    })

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
