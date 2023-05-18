<script>
export default {
    data() {
        return {
            search: '',
        }
    },
    mounted() {
        console.log("AHoj")
    },
    render() {
        return this.$scopedSlots.default({
            search: this.search,
        })
    }
}
</script>
