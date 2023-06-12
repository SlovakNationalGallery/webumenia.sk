<script>
export default {
    methods: {
        reload() {
            location.reload()
        },
    },
    render() {
        return this.$scopedSlots.default({
            reload: this.reload,
        })
    },
}
</script>
