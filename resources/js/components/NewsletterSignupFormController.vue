<script setup>
import { ref } from 'vue'
import { useApiClient } from './useApiClient'

const apiClient = useApiClient()

const props = defineProps(['url', 'trackingVariant'])

const error = ref(null)
const success = ref(false)
const loading = ref(false)

async function submit(event) {
    const data = new FormData(event.target)

    loading.value = true

    try {
        await apiClient.post(props.url, data)
        error.value = null
        success.value = true
        track('signupSuccessful', props.trackingVariant)
    } catch (e) {
        error.value = e.response.data.message
    } finally {
        loading.value = false
    }
}

function track(action, label = undefined) {
    if (!window?.dataLayer) {
        return
    }

    window.dataLayer.push({
        event: 'NewsletterSignupFormEvent',
        eventCategory: 'NewsletterSignupForm',
        eventAction: action,
        eventLabel: label,
    })
}
</script>

<template>
    <slot
        :error="error"
        :success="success"
        :loading="loading"
        :submit="submit"
        :track="track"
    ></slot>
</template>
