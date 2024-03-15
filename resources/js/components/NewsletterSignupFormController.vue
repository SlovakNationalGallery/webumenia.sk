<script setup>
import { ref } from 'vue'
import { useApiClient } from './useApiClient'

const apiClient = useApiClient()

const props = defineProps(['url'])

const error = ref(null)
const success = ref(false)
const loading = ref(false)

async function submit(event) {
    const data = new FormData(event.target)

    loading.value = true

    try {
        await apiClient.post(props.url, data)
        success.value = true
        error.value = null
    } catch (e) {
        error.value = e.response.data.message
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <slot :error="error" :success="success" :loading="loading" :submit="submit"></slot>
</template>
