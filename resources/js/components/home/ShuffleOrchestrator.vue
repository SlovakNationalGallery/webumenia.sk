<script>
const items = [
    {
        id: 1,
        filters: [
            {
                url: 'http://localhost:8000/en/katalog?work_type=maliarstvo&object_type=easel+painting&topic=po%C4%BEovn%C3%ADcky+mot%C3%ADv&medium=canvas&years-range=1500%2C1927',
                attributes: [
                    { label: 'v\u00fdtvarn\u00fd druh', value: 'maliarstvo' },
                    {
                        label: '\u017e\u00e1ner',
                        value: 'po\u013eovn\u00edcky mot\u00edv',
                    },
                    { label: 'typ diela', value: 'easel painting' },
                ],
            },
            {
                url: 'http://localhost:8000/en/katalog?work_type=maliarstvo&object_type=easel+painting&topic=po%C4%BEovn%C3%ADcky+mot%C3%ADv&medium=canvas&years-range=1500%2C1927',
                attributes: [
                    { label: 'v\u00fdtvarn\u00fd druh', value: 'maliarstvo' },
                    { label: 'typ diela', value: 'easel painting' },
                    { label: 'materi\u00e1l', value: 'canvas' },
                ],
            },
        ],
        img: {
            src: 'http://127.0.0.1:8000/storage/media/8/file.jpeg',
            srcset: 'http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_1600_929.jpeg 1600w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_1338_776.jpeg 1338w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_1120_650.jpeg 1120w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_937_544.jpeg 937w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_783_454.jpeg 783w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_655_380.jpeg 655w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_548_318.jpeg 548w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_459_266.jpeg 459w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_384_222.jpeg 384w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_321_186.jpeg 321w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_268_155.jpeg 268w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_224_130.jpeg 224w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_188_109.jpeg 188w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_157_91.jpeg 157w, data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTYwMCA5MjkiPgoJPGltYWdlIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjkyOSIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwvOWovNEFBUVNrWkpSZ0FCQVFFQVlBQmdBQUQvL2dBN1ExSkZRVlJQVWpvZ1oyUXRhbkJsWnlCMk1TNHdJQ2gxYzJsdVp5QkpTa2NnU2xCRlJ5QjJPVEFwTENCeGRXRnNhWFI1SUQwZ09UQUsvOXNBUXdBREFnSURBZ0lEQXdNREJBTURCQVVJQlFVRUJBVUtCd2NHQ0F3S0RBd0xDZ3NMRFE0U0VBME9FUTRMQ3hBV0VCRVRGQlVWRlF3UEZ4Z1dGQmdTRkJVVS85c0FRd0VEQkFRRkJBVUpCUVVKRkEwTERSUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVLzhBQUVRZ0FFZ0FlQXdFaUFBSVJBUU1SQWYvRUFCOEFBQUVGQVFFQkFRRUJBQUFBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUUFBSUJBd01DQkFNRkJRUUVBQUFCZlFFQ0F3QUVFUVVTSVRGQkJoTlJZUWNpY1JReWdaR2hDQ05Dc2NFVlV0SHdKRE5pY29JSkNoWVhHQmthSlNZbktDa3FORFUyTnpnNU9rTkVSVVpIU0VsS1UxUlZWbGRZV1ZwalpHVm1aMmhwYW5OMGRYWjNlSGw2ZzRTRmhvZUlpWXFTazVTVmxwZVltWnFpbzZTbHBxZW9xYXF5czdTMXRyZTR1YnJDdzhURnhzZkl5Y3JTMDlUVjF0ZlkyZHJoNHVQazVlYm42T25xOGZMejlQWDI5L2o1K3YvRUFCOEJBQU1CQVFFQkFRRUJBUUVBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUkFBSUJBZ1FFQXdRSEJRUUVBQUVDZHdBQkFnTVJCQVVoTVFZU1FWRUhZWEVUSWpLQkNCUkNrYUd4d1Frak0xTHdGV0p5MFFvV0pEVGhKZkVYR0JrYUppY29LU28xTmpjNE9UcERSRVZHUjBoSlNsTlVWVlpYV0ZsYVkyUmxabWRvYVdwemRIVjJkM2g1ZW9LRGhJV0doNGlKaXBLVGxKV1dsNWlabXFLanBLV21wNmlwcXJLenRMVzJ0N2k1dXNMRHhNWEd4OGpKeXRMVDFOWFcxOWpaMnVMajVPWG01K2pwNnZMejlQWDI5L2o1K3YvYUFBd0RBUUFDRVFNUkFEOEErUGRNSzZyZFJ3RVlVbnJYWVRYZGo0YmhOc1Yza2pyWEo2SW45alg2U1RmdzlSUytKOVJHcVhaa2o2ZTFmSnlqenlzdGoxMCtXTGIzS3QxZVJ5WFV1MFlSdWxaelFBc2FsZ2hCWWIrRFdqYzZVaUlqQ1FmTjJyYUxqQmlmUFZWcmJEL0VIRjA5WjF0eXArdEZGRWY0YUlxL3hXVHpENWwrbE9tSktKeWFLS1VlaFQ2bi85az0iPgoJPC9pbWFnZT4KPC9zdmc+ 32w',
        },
    },
    {
        id: 6,
        filters: [
            {
                url: 'http://localhost:8000/katalog?work_type=maliarstvo%2Fobraz+z%C3%A1vesn%C3%BD&technique=olej&gallery=Slovensk%C3%A1+n%C3%A1rodn%C3%A1+gal%C3%A9ria%2C+SNG&years-range=1500%2C1927',
                attributes: [
                    {
                        label: 'v\u00fdtvarn\u00fd druh',
                        value: 'obraz z\u00e1vesn\u00fd',
                    },
                    {
                        label: 'gal\u00e9ria',
                        value: 'Slovensk\u00e1 n\u00e1rodn\u00e1 gal\u00e9ria',
                    },
                    { label: 'technika', value: 'olej' },
                ],
            },
        ],
        img: {
            src: 'http://127.0.0.1:8000/storage/media/12/file.jpeg',
            srcset: 'http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_1600_900.jpeg 1600w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_1338_752.jpeg 1338w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_1119_629.jpeg 1119w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_937_527.jpeg 937w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_783_440.jpeg 783w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_655_368.jpeg 655w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_548_308.jpeg 548w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_459_258.jpeg 459w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_384_216.jpeg 384w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_321_180.jpeg 321w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_268_150.jpeg 268w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_224_126.jpeg 224w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_188_105.jpeg 188w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_157_88.jpeg 157w, data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTYwMCA5MDAiPgoJPGltYWdlIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjkwMCIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwvOWovNEFBUVNrWkpSZ0FCQVFFQVlBQmdBQUQvL2dBN1ExSkZRVlJQVWpvZ1oyUXRhbkJsWnlCMk1TNHdJQ2gxYzJsdVp5QkpTa2NnU2xCRlJ5QjJPVEFwTENCeGRXRnNhWFI1SUQwZ09UQUsvOXNBUXdBREFnSURBZ0lEQXdNREJBTURCQVVJQlFVRUJBVUtCd2NHQ0F3S0RBd0xDZ3NMRFE0U0VBME9FUTRMQ3hBV0VCRVRGQlVWRlF3UEZ4Z1dGQmdTRkJVVS85c0FRd0VEQkFRRkJBVUpCUVVKRkEwTERSUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVLzhBQUVRZ0FFZ0FnQXdFaUFBSVJBUU1SQWYvRUFCOEFBQUVGQVFFQkFRRUJBQUFBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUUFBSUJBd01DQkFNRkJRUUVBQUFCZlFFQ0F3QUVFUVVTSVRGQkJoTlJZUWNpY1JReWdaR2hDQ05Dc2NFVlV0SHdKRE5pY29JSkNoWVhHQmthSlNZbktDa3FORFUyTnpnNU9rTkVSVVpIU0VsS1UxUlZWbGRZV1ZwalpHVm1aMmhwYW5OMGRYWjNlSGw2ZzRTRmhvZUlpWXFTazVTVmxwZVltWnFpbzZTbHBxZW9xYXF5czdTMXRyZTR1YnJDdzhURnhzZkl5Y3JTMDlUVjF0ZlkyZHJoNHVQazVlYm42T25xOGZMejlQWDI5L2o1K3YvRUFCOEJBQU1CQVFFQkFRRUJBUUVBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUkFBSUJBZ1FFQXdRSEJRUUVBQUVDZHdBQkFnTVJCQVVoTVFZU1FWRUhZWEVUSWpLQkNCUkNrYUd4d1Frak0xTHdGV0p5MFFvV0pEVGhKZkVYR0JrYUppY29LU28xTmpjNE9UcERSRVZHUjBoSlNsTlVWVlpYV0ZsYVkyUmxabWRvYVdwemRIVjJkM2g1ZW9LRGhJV0doNGlKaXBLVGxKV1dsNWlabXFLanBLV21wNmlwcXJLenRMVzJ0N2k1dXNMRHhNWEd4OGpKeXRMVDFOWFcxOWpaMnVMajVPWG01K2pwNnZMejlQWDI5L2o1K3YvYUFBd0RBUUFDRVFNUkFEOEErdGJlK2p0NFFaWENnZHlhc1ErSTdHWmhHbHdqTjZacjVGK0lIeHZ2N3l4VzN0SkRHejhaQnJ6ZTMrS2V0K0hyeUo1THhwSkNjNDNWODIrWmFIdWM4WHNmWlh4YThRTnAyaFN0YnVBK0t4UGc1NHBON3BMbTVsRzhIdlhoZXFmRnVYeERwU0xjRWtsZWMxUTBMNGt4NkJiTUJONVlKOWFoUms1QTZrYkhtWGlWaUkwT1Qxcmo1M1o5WGkzTVc2ZFRSUlc4dHpLbHNkN2Evd0RIc3RjbDR0ZGx5QXhIME5GRlhUM1JsUDRXZi8vWiI+Cgk8L2ltYWdlPgo8L3N2Zz4= 32w',
        },
    },
]

// https://stackoverflow.com/a/2450976
function shuffle(array) {
    let currentIndex = array.length,
        randomIndex

    // While there remain elements to shuffle.
    while (currentIndex != 0) {
        // Pick a remaining element.
        randomIndex = Math.floor(Math.random() * currentIndex)
        currentIndex--

        // And swap it with the current element.
        ;[array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]]
    }

    return array
}

function pickRandom(array) {
    return array[Math.floor(Math.random() * (array.length - 1))]
}

export default {
    data() {
        const shuffledItems = shuffle(items)
        const initialFilter = pickRandom(shuffledItems[0].filters)

        return {
            items,
            shuffledItems,
            itemIndex: 0,
            isShuffling: false,
            filterAttributes: initialFilter.attributes,
            url: initialFilter.url,
        }
    },
    // props: {
    //     id: {
    //         type: Number,
    //         required: true,
    //     },
    //     url: {
    //         type: String,
    //         required: true,
    //     },
    // },
    render() {
        return this.$scopedSlots.default({
            isShuffling: this.isShuffling,
            filterAttributes: this.filterAttributes,
            url: this.url,
            img: {
                ...this.shuffledItems[this.itemIndex].img,
                classList: this.imgClassList,
            },
            overlay: {
                classList: this.overlayClassList,
            },
            shuffle: () => {
                if (this.isShuffling) return

                this.isShuffling = true

                const shuffledFilters = shuffle(this.items.flatMap((si) => si.filters))
                let shuffledFiltersIndex = 0

                const filterShuffle = setInterval(() => {
                    ;(this.filterAttributes = shuffle(
                        shuffledFilters[shuffledFiltersIndex].attributes
                    )), // TODO make this so that the combinations don't repeat
                        (shuffledFiltersIndex = (shuffledFiltersIndex + 1) % shuffledFilters.length)
                }, 300)

                setTimeout(() => {
                    clearInterval(filterShuffle)
                    this.itemIndex = (this.itemIndex + 1) % this.items.length

                    const item = this.shuffledItems[this.itemIndex]
                    const filterIndex = Math.floor(Math.random() * (item.filters.length - 1))

                    this.filterAttributes = item.filters[filterIndex].attributes
                    this.url = item.filters[filterIndex].url

                    this.isShuffling = false
                }, 3000)
            },
        })
    },
}
</script>
