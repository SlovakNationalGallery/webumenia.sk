<template>
    <v-select
        v-on:search="search"
        :options="options"
        :filterable="false"
        :label="optionLabel"
        :clearable="false"
        :value="value"
        :placeholder="placeholder"
        v-on:input="(value) => $emit('input', value)"
    >
        <template v-slot:option="option">
            <slot name="option" v-bind="option"> {{ option.id }}aaa </slot>
        </template>
    </v-select>
</template>

<script>
import Bloodhound from "corejs-typeahead/dist/bloodhound"
import VueSelect from "vue-select"

const defaultRemote = {
    wildcard: "%QUERY",
}

export default {
    components: {
        "v-select": VueSelect,
    },
    props: {
        remote: Object,
        optionLabel: String,
        value: String,
        placeholder: String,
    },
    data() {
        return {
            options: [],
        }
    },
    created() {
        this.$bloodhound = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                ...defaultRemote,
                ...this.remote,
            },
        })
    },
    methods: {
        search(query, loading) {
            if (!query) return

            loading(true)

            this.$bloodhound.search(
                query,
                (syncResults) => {
                    this.options = syncResults
                },
                (asyncResults) => {
                    loading(false)
                    this.options = asyncResults
                }
            )
        },
    },
}
</script>
