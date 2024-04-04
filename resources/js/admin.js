import 'vue-select/dist/vue-select.css'

import { createApp } from 'vue'
import { i18nVue } from 'laravel-vue-i18n'

import LinksInput from './components/admin/LinksInput.vue'
import Autocomplete from './components/Autocomplete.vue'
import LinkedCombos from './components/vue/linked-combos.vue'
import QueryString from './components/QueryString.vue'
import Croppr from './components/Croppr.vue'
import TabsController from './components/TabsController.vue'
import Tab from './components/Tab.vue'
import TabPanel from './components/TabPanel.vue'
import ShuffleItemFilters from './components/admin/ShuffleItemFilters.vue'

createApp()
    .use(i18nVue, {
        resolve: async (lang) => {
            const langs = import.meta.glob('../../lang/*.json')
            return await langs[`../../lang/${lang}.json`]()
        },
    })

    .component('admin-links-input', LinksInput)
    .component('autocomplete', Autocomplete)
    .component('linked-combos', LinkedCombos)
    .component('query-string', QueryString)
    .component('croppr', Croppr)
    .component('tabs-controller', TabsController)
    .component('tab', Tab)
    .component('tab-panel', TabPanel)
    .component('admin.shuffle-item-filters', ShuffleItemFilters)

    .mount('#wrapper')
