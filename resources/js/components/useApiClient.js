import axios from 'axios'

export function useApiClient() {
    return axios.create({
        headers: {
            'Accept-Language': document.documentElement.lang,
        },
    })
}
