<template>
    <div v-if="currentValue || !disabled" class="inline_input">
        <div class="inline_input__spacer">{{ currentValue || placeholder }}</div>
        <div class="inline_input__textarea-wrapper" v-if="!disabled">
            <textarea
                ref="input"
                v-model="currentValue"
                v-bind="$attrs"
                v-on="$listeners"

                :placeholder="placeholder"
                :aria-label="placeholder"
                @keydown.enter.prevent
            /></textarea>
        </div>
        <div class="inline_input__pencil" v-if="!disabled">
            <i class="fa fa-pencil"></i>
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
        focused: Boolean,
    },
    data() {
        return {
            currentValue: this.value,
        }
    },
    mounted() {
        if (this.focused) this.$nextTick(function () {
            this.$refs.input.focus()
        })
    },
}
</script>
