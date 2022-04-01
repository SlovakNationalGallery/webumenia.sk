<script>
export default {
    props: {
        value: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            id: this.value.length,
            // Add an `id` to defaultItems
            items: this.value.map((item, index) => ({ id: index, ...item })),
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
