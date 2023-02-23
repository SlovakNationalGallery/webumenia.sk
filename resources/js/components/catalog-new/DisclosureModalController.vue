<script>
export default {
    data() {
        return {
            view: null,
        }
    },
    methods: {
        goTo(view) {
            this.view = view
        },
        close() {
            this.view = null
        },
    },
    render() {
        return this.$scopedSlots.default({
            view: this.view,
            goTo: this.goTo,
            close: this.close,
        })
    },
}
</script>
