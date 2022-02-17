<script>
// Component for passing messages between Vue and Livewire.
// Must be placed at root of Livewire component (so that it gets wire:id attribute), or passed instanceId

export default {
    props: {
        instanceId: String,
    },
    computed: {
        target() {
            const instanceId = this.instanceId || this.$attrs["wire:id"]
            return window.livewire.find(instanceId)
        },
    },
    render() {
        return this.$scopedSlots.default({
            call: (methodName, ...args) => this.target[methodName](...args),
            set: (propertyName, value) => {
                this.target[propertyName] = value
            },
        })
    },
}
</script>
