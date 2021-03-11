<template>
    <div class="dropdown">
        <a class="dropdown-toggle" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="false">{{ label }} {{ selectedOption.text }} <span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" arialabelledby="dropdownSortBy">
            <li v-for="option in selectableOptions" role="presentation">
                <a class="cursor-pointer" role="menuitem" tabindex="-1" @click="value = option.value">{{ option.text }}</a>
            </li>
        </ul>
    </div>
</template>

<script>
import { addParameterToQuery } from './filter'

export default {
    props: {
        label: String,
        initialValue: String,

        //Expects the shape of [{ value: string|number, text: string }]
        options: Array, 
    },
    data() {
        return {
            value: this.initialValue
        }
    },
    computed: {
        selectedOption() {
            return this.options.find(({value}) => value === this.value)
        },
        selectableOptions() {
            return this.options.filter(({value}) => value !== this.value)
        }
    },
    watch: {
        value(value) {
            addParameterToQuery('sort_by', value)
        }
    },
}
</script>
