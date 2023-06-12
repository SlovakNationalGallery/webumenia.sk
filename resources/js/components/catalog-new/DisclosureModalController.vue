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
            document.body.style.overflow = 'hidden'
        },
        close() {
            this.view = null
            document.body.style.overflow = 'auto'
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
