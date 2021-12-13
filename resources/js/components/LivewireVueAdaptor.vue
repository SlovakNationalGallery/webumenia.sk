<template>
    <div >
        <slot :call="call"></slot>
    </div>
</template>

<script>

// Component for passing messages between Vue and Livewire.
// Must be placed at root of Livewire component (so that it gets wire:id attribute)

export default {
    methods: {
        call(methodName, ...args) {
            this.target[methodName](args)
        }
    },
    computed: {
        target() {
            return window.livewire.find(this.$attrs['wire:id'])
        }
    }
}
</script>