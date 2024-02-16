import { onMounted, ref } from 'vue'

export function useTitleUpdater(staticPartSeparator) {
    const staticPart = ref('')

    onMounted(() => {
        const titleParts = document.title.split(staticPartSeparator)
        staticPart.value = `${staticPartSeparator} ${titleParts.slice(1).join('')}`
    })

    async function refreshTitle(querySearchParams) {
        const response = await fetch(`/api/v1/items/catalog-title?${querySearchParams}`, {
            headers: {
                Accept: 'application/json',
            },
        })
        if (!response.ok) return

        const title = await response.text()
        document.title = `${title} | ${staticPart.value}`
    }

    return {
        refreshTitle,
    }
}
