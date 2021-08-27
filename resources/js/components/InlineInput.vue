<template>
    <div v-if="currentValue || !disabled" class="inline_input">
        <div class="inline_input__spacer">{{ currentValue || placeholder }}</div>
        <div class="inline_input__textarea-wrapper" v-if="!disabled">
            <textarea 
                v-model="currentValue"
                v-bind="$attrs"
                v-on="$listeners"

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
    data() {
        return {
            currentValue: this.value,
        }
    },
}
</script>
