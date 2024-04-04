<template>
    <select ref="select" class="form-control"></select>
</template>

<script>
import { addParameterToQuery } from './filter'

export default {
    props: {
        name: String,
        placeholder: String,

        //Expects the shape of [{value: string | number, text: string, selected: boolean }]
        options: Object, 
    },
    mounted() {
        const { placeholder, name } = this;
    
        $(this.$refs.select).selectize({
            plugins: ["remove_button"],
            placeholder,
            maxItems: 1,
            mode: "multi",
            render: {
                item: (data, escape) => (
                    `<div class="selected-item"><span class="color">${placeholder}: </span>${data.text.replace(/\(.*?\)/g, "")}</div>`
                )
            },
            options: this.options,
            items: this.options.filter((option) => option.selected).map((option) => option.value),
            onChange: (value) => addParameterToQuery(name, value)
        })
    },
}
</script>
