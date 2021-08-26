<template>
    <div class="inline_input">
        <div class="inline_input__spacer">{{ value || placeholder }}</div>
        <div class="inline_input__textarea-wrapper" v-if="!disabled">
            <textarea 
                v-bind="$attrs"
                v-bind:value="value"
                v-on="inputListeners"

                :placeholder="placeholder"
                :aria-label="placeholder"
                @keydown.enter.prevent
            /></textarea>
        </div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        value: String,
        placeholder: String,
        disabled: Boolean,
    },
    // https://vuejs.org/v2/guide/components-custom-events.html#Binding-Native-Events-to-Components
    computed: {
        inputListeners: function () {
            var vm = this
            // `Object.assign` merges objects together to form a new object
            return Object.assign({},
                // We add all the listeners from the parent
                this.$listeners,
                // Then we can add custom listeners or override the
                // behavior of some listeners.
                {
                    // This ensures that the component works with v-model
                    input: function (event) {
                        vm.$emit('input', event.target.value)
                    }
                }
            )
        }
    },
}
</script>
