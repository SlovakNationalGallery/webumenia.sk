<script>
export default {
    methods: {
        reload() {
            location.reload()
        },
    },
    render() {
        return this.$slots.default({
            reload: this.reload,
        })
    },
}
</script>
