import { onMounted, ref } from 'vue'
import { useApiClient } from '../useApiClient'

export function useTitleUpdater(staticPartSeparator) {
    const staticPart = ref('')
    const apiClient = useApiClient()

    onMounted(() => {
        const titleParts = document.title.split(staticPartSeparator)
        staticPart.value = `${staticPartSeparator} ${titleParts.slice(1).join('')}`
    })

    async function refreshTitle(querySearchParams) {
        if (!querySearchParams) {
            document.title = staticPart.value
            return
        }

        const title = await apiClient
            .get(`/api/v1/items/catalog-title?${querySearchParams}`)
            .then(({ data }) => data.title)

        document.title = `${title} | ${staticPart.value}`
    }

    return {
        refreshTitle,
    }
}
