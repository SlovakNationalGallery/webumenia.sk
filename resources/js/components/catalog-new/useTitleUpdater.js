import { onMounted, ref } from 'vue'
import axios from 'axios'

export function useTitleUpdater(staticPartSeparator, acceptLanguage) {
    const staticPart = ref('')

    onMounted(() => {
        const titleParts = document.title.split(staticPartSeparator)
        staticPart.value = `${staticPartSeparator} ${titleParts.slice(1).join('')}`
    })

    async function refreshTitle(querySearchParams) {
        if (!querySearchParams) {
            document.title = staticPart.value
            return
        }

        const title = await axios
            .get(`/api/v1/items/catalog-title?${querySearchParams}`, {
                headers: {
                    'Accept-Language': acceptLanguage,
                },
            })
            .then(({ data }) => data.title)

        document.title = `${title} | ${staticPart.value}`
    }

    return {
        refreshTitle,
    }
}
