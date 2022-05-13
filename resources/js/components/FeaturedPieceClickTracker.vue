<script>
export default {
    props: {
        id: {
            type: Number,
            required: true,
        },
        url: {
            type: String,
            required: true,
        },
    },
    render() {
        return this.$scopedSlots.default({
            track: () => {
                fetch('/api/track-featured-piece-click', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: this.id }),
                }).finally(() => (window.location.href = this.url))
            },
        })
    },
}
</script>
