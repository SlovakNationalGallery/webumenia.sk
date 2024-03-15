<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { debounce } from 'debounce'
import { computed } from 'vue'
import { useApiClient } from '../composables/useApiClient'
import { useFormEvent } from './useFormEvent'

const { track } = useFormEvent()
const apiClient = useApiClient()

const maxScrolledPercent = ref(0)
const timeSpentInSeconds = ref(0)
const timeSpentInterval = ref(null)

const TimeSpentRefreshRate = 5 // seconds
const ShowOnTimeSpent = 30 // seconds

const onScrollDebounced = debounce(onScroll, 500)

const props = defineProps({ showOnScrolledPercent: Number, dismissalUrl: String })

onMounted(() => {
    window.addEventListener('scroll', onScrollDebounced)
    timeSpentInterval.value = setInterval(updateTimeSpent, TimeSpentRefreshRate * 1000)
})

onBeforeUnmount(() => {
    clearInterval(timeSpentInterval.value)
    window.removeEventListener('scroll', onScrollDebounced)
})

function onScroll() {
    const percentScrolled = Math.floor(
        (window.scrollY / (document.body.scrollHeight - document.body.clientHeight)) * 100
    )
    maxScrolledPercent.value = Math.max(maxScrolledPercent.value, percentScrolled)
}

function updateTimeSpent() {
    timeSpentInSeconds.value += TimeSpentRefreshRate
}

const show = computed(() => {
    return (
        maxScrolledPercent.value >= props.showOnScrolledPercent ||
        timeSpentInSeconds.value >= ShowOnTimeSpent
    )
})

function onOpen() {
    track('modalOpen')
}

function onDismiss() {
    track('modalDismissed')
    apiClient.post(props.dismissalUrl)
}
</script>

<template>
    <slot :show="show" :onOpen="onOpen" :onDismiss="onDismiss"></slot>
</template>
