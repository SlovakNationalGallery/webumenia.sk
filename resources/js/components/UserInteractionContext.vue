<template>
    <div @scroll="onScroll">
        <slot :maxPercentScrolled="maxPercentScrolled"></slot>
    </div>
</template>

<script>
import { debounce } from "debounce";

export default {
    data() {
        return {
            maxPercentScrolled: 0
        }
    },
    created() {
        this.onScrollDebounced = debounce(this.onScroll, 500);
    },
    mounted() {
        window.addEventListener('scroll', this.onScrollDebounced);
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.onScrollDebounced);
    },
    methods: {
        onScroll(event) {
            const percentScrolled = Math.floor(window.scrollY / (document.body.scrollHeight - document.body.clientHeight) * 100)
            this.maxPercentScrolled = Math.max(this.maxPercentScrolled, percentScrolled);
        }
    },

}
</script>