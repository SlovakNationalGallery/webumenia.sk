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
            store: this.store,
        })
    },
}
</script>
