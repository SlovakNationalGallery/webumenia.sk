<script>
const store = require('../user-collections/store')

export default {
    data() {
        return {
            store,
        }
    },
    render() {
        return this.$scopedSlots.default({
            toggleItem: this.store.toggleItem.bind(this.store),
            hasItem: this.store.hasItem.bind(this.store),
        })
    },
}
</script>
