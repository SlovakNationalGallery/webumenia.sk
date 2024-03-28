<script>
export default {
    data() {
        return {
            isLoaded: false,
        }
    },
    methods: {
        onImgLoad() {
            this.isLoaded = true
        },
    },
    render() {
        return this.$slots.default({
            isLoaded: this.isLoaded,
            onImgLoad: this.onImgLoad,
        })
    },
}
</script>
