<template>
    <div class="inline_input">
        <div class="inline_input__spacer">{{ currentValue || placeholder }}</div>
        <div class="inline_input__textarea-wrapper" v-if="!disabled">
            <textarea 
                :name="name" 
                :placeholder="placeholder"
                :aria-label="placeholder"
                v-model="currentValue"
                @keydown.enter.prevent 
            /></textarea>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        value: String,
        placeholder: String,
        disabled: Boolean,
    },
    data() {
        return {
            currentValue: this.value
        }
    }
}
</script>
