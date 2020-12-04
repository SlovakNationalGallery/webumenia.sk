<template>
    <button @click="clearAllItems">
        <slot/>
    </button>
</template>

<script>
    export default {
        props: ['confirmMessage'],
        data() {
            return {
                store: this.$root.$data.userCollectionsStore
            }
        },
        methods: {
            clearAllItems() {
                if (confirm(this.confirmMessage)) {
                    this.store.clearAllItems()
                }
            }
        }
    }
</script>
