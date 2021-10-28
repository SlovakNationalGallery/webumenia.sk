<template>
    <div>
        <slot :sharedCollections="sharedCollections" ></slot>
    </div>
</template>

<script>
export default {
    data() {
        return {
            store: require('./store'),
        }
    },
    computed: {
        sharedCollections() {
            return this.store.getSharedCollections()
        },
    }
}
</script>
