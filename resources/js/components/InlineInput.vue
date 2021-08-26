<template>
    <div class="inline_input__container">
        <div class="inline_input__spacer">{{ currentValue || placeholder }}</div>
        <textarea 
          :name="name" 
          :placeholder="placeholder"
          :aria-label="placeholder"
          v-model="currentValue"
          @keydown.enter.prevent /></textarea>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        value: String,
        placeholder: String
    },
    data() {
        return {
            currentValue: this.value
        }
    }
}
</script>
