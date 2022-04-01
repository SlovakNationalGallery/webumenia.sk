<script>
export default {
    props: {
        // NOTE default items must have an `id` parameter
        defaultItems: {
            type: Array,
        },
    },
    data() {
        return {
            id: 0,
            items: this.defaultItems || [],
        }
    },
    render() {
        return this.$scopedSlots.default({
            items: this.items,
            push: (item) => this.items.push({ id: this.id++, ...item }),
            delete: (index) => {
                this.items.splice(index, 1)
            },
        })
    },
}
</script>
