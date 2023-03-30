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
    },
    render() {
        return this.$scopedSlots.default({
            items: this.items,
            sharedCollections: this.sharedCollections,
            toggleItem: this.store.toggleItem.bind(this.store),
            hasItem: this.store.hasItem.bind(this.store),
        })
    },
}
</script>
