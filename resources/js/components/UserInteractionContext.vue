<template>
    <div @scroll="onScroll">
        <slot :maxScrolledPercent="maxScrolledPercent" :timeSpentSeconds="timeSpent"></slot>
    </div>
</template>

<script>
import { debounce } from "debounce";

const TimeSpentRefreshRate = 5; // seconds

export default {
    data() {
        return {
            maxScrolledPercent: 0,
            timeSpent: 0,
        }
    },
    created() {
        this.onScrollDebounced = debounce(this.onScroll, 500);
        this.timeSpentInterval = setInterval(this.updateTimeSpent, TimeSpentRefreshRate * 1000);
    },
    mounted() {
        window.addEventListener('scroll', this.onScrollDebounced);
    },
    beforeDestroy() {
        clearInterval(this.timeSpentInterval);
        window.removeEventListener('scroll', this.onScrollDebounced);
    },
    methods: {
        onScroll(event) {
            const percentScrolled = Math.floor(window.scrollY / (document.body.scrollHeight - document.body.clientHeight) * 100)
            this.maxScrolledPercent = Math.max(this.maxScrolledPercent, percentScrolled);
        },
        updateTimeSpent() {
            this.timeSpent += TimeSpentRefreshRate;
        }
    },

}
</script>