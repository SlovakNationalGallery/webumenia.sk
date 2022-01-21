<template>
    <div class="checkbox">
        <input type="checkbox" :id="name" v-model="checked">
        <label :for="name">{{ label }}</label>
    </div>
</template>

<script>
import { addParameterToQuery } from './filter'

export default {
    props: {
        name: String,
        label: String,
        initialChecked: Boolean
    },
    data() {
        return {
            checked: this.initialChecked
        }
    },
    watch: {
        checked(value) {
            addParameterToQuery(this.name, value)
        }
    },
}
</script>
