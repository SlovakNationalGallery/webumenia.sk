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
            const collections = this.store.getSharedCollections()

            return {
                all: collections,
                present: collections.length > 0,
            }
        },
    }
}
</script>
