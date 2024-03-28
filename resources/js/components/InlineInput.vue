<template>
    <div
        v-if="currentValue || !disabled"
        class="inline_input"
        :class="[{ border: !disabled }, $attrs.class]"
    >
        <div class="inline_input__spacer">{{ currentValue || placeholder }}</div>
        <div class="inline_input__textarea-wrapper" v-if="!disabled">
            <textarea
                v-bind="this.attrsExceptClass"
                v-model="currentValue"
                ref="input"
                :placeholder="placeholder"
                :aria-label="placeholder"
                @keydown.enter.prevent
            />
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
    computed: {
        attrsExceptClass() {
            const { class: cls, ...rest } = this.$attrs
            return rest
        },
    },
    mounted() {
        if (this.focused)
            this.$nextTick(function () {
                this.$refs.input.focus()
            })
    },
}
</script>
