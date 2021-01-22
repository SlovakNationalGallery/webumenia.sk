<template>
    <button class="btn btn-sm btn-default btn-outline sans ml-4 mb-2" @click="clearAllItems">
        <slot/>
        <i class="icon-cross"></i>
    </button>
</template>

<script>
    export default {
        props: ['confirmMessage', 'afterClearRedirect'],
        data() {
            return {
                store: this.$root.$data.userCollectionsStore
            }
        },
        methods: {
            clearAllItems() {
                if (confirm(this.confirmMessage)) {
                    this.store.clearAllItems()
                    window.location.href = this.afterClearRedirect
                }
            }
        }
    }
</script>
