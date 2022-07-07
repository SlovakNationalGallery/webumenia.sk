<script>
// Shuffles an array
// https://stackoverflow.com/a/2450976
function shuffle(inputArray) {
    const array = [...inputArray] // Do not mutate the original array
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

async function sleep(timeInMs) {
    await new Promise((r) => setTimeout(r, timeInMs))
}

export default {
    props: {
        items: {
            type: Array,
            required: true,
        },
        initialItemId: {
            type: Number,
        },
    },
    data() {
        const shuffledItems = shuffle(this.items)

        // Allow picking the first item from prop
        const initialItemIndex = (() => {
            if (this.initialItemId === undefined) return 0

            const initialItemIndex = shuffledItems.findIndex(
                (item) => item.id === this.initialItemId
            )

            return initialItemIndex > -1 ? initialItemIndex : 0
        })()

        return {
            shuffledItems,
            itemIndex: initialItemIndex,
            filter: pickRandom(shuffledItems[initialItemIndex].filters),
            isShuffling: false,
        }
    },
    computed: {
        nextItemIndex() {
            return (this.itemIndex + 1) % this.items.length
        },
        item() {
            const { title, authorLinks, datingFormatted, url, img } =
                this.shuffledItems[this.itemIndex]
            return {
                title,
                authors: authorLinks.map((al) => al.label).join(', '),
                dating: datingFormatted,
                url,
                img,
            }
        },
    },
    render() {
        return this.$scopedSlots.default({
            isShuffling: this.isShuffling,
            filter: this.filter,
            item: this.item,
            nextImg: this.shuffledItems[this.nextItemIndex].img,
            shuffle: async () => {
                if (this.isShuffling) return

                this.isShuffling = true

                const nextItem = this.shuffledItems[this.nextItemIndex]
                const nextFilter =
                    nextItem.filters[Math.floor(Math.random() * (nextItem.filters.length - 1))]

                // Start shuffling filters
                const numberOfShuffles = 4
                const shuffledFilters = shuffle(this.items.flatMap((si) => si.filters))

                await sleep(300)

                for (let iteration = 0; iteration < numberOfShuffles - 1; iteration++) {
                    const shuffledFiltersIndex = iteration % shuffledFilters.length

                    this.filter = {
                        ...this.filter,
                        attributes: shuffle(shuffledFilters[shuffledFiltersIndex].attributes),
                    }

                    await sleep(400)
                }

                // Settle filter
                this.filter = nextFilter

                // Change image
                await sleep(400)
                this.itemIndex = this.nextItemIndex
                this.isShuffling = false
            },
        })
    },
}
</script>
