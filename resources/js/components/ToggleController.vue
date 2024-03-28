<script>
export default {
    data() {
        return {
            isOn: false,
        }
    },
    methods: {
        toggle() {
            this.isOn = !this.isOn
        },
    },
    render() {
        return this.$slots.default({
            isOn: this.isOn,
            toggle: this.toggle,
        })
    },
}
</script>
