<template>
    <transition enter-active-class="animated slideInUp faster" leave-active-class="animated slideOutDown faster">
        <div v-if="show && !closed" class="bg-blue position-fixed left-0 bottom-0 right-0" style="z-index: 2">
            <div class="container">
                <button @click="close" class="position-absolute right-0 top-0 btn btn-link hover:text-black py-3" style="z-index: 3">
                    <svg class="mt-1" style="width: 20px" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#000" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <slot></slot>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false
        }
    },
    data: function () {
        return {
            closed: false
        }
    },
    methods: {
        close: function () {
            this.closed = true
            this.$emit('close')
        }
    }
}
</script>
