<script>
export default {
    render() {
        return this.$scopedSlots.default({
            set: (name, value) => {
                const url = new URL(window.location.href)
                value
                    ? url.searchParams.set(name, value)
                    : url.searchParams.delete(name)

                window.location.href = url
            },
        })
    },
}
</script>
