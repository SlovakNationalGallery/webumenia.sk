<template>
    <div>
        <slot :sharedCollections="sharedCollections" :items="items"></slot>
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
            const collections = this.store.getSharedCollections()

            return {
                all: collections,
                present: collections.length > 0,
            }
        },
        items() {
            const items = this.store.getItems()

            return {
                all: items,
                present: items.length > 0,
            }
        },
    }
}
</script>
