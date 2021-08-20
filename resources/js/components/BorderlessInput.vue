<template>
    <div class="borderless__container">
        <div class="borderless__spacer">{{ currentValue || placeholder }}</div>
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
