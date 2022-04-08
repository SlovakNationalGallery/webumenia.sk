<script>
const RecognizedAttributes = [
    'author',
    'work_type',
    'topic',
    'object_type',
    'place',
    'medium',
    'tag',
    'gallery',
    'technique',
]

function getAttributesFromUrl(url) {
    try {
        const searchParams = new URL(url).searchParams
        const attributes = {}

        RecognizedAttributes.forEach((attribute) => {
            const value = searchParams.get(attribute)
            if (value) attributes[attribute] = value
        })

        return attributes
    } catch (error) {
        return {}
    }
}

export default {
    props: {
        value: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            idCounter: this.value.length,

            // Add an ID to each element to allow for list management
            filters: this.value.map((filter, index) => ({ ...filter, _id: index })),
        }
    },
    computed: {
        filtersOutput() {
            return this.filters.map((f, filterIndex) => {
                const attributesInUrl = getAttributesFromUrl(f.url)

                return {
                    ...f,
                    onUrlInput: (event) => {
                        this.filters[filterIndex].url = event.target.value
                    },
                    attributeChoices: Object.keys(attributesInUrl).map((ac) => ({
                        value: ac,
                        translation: this.__(`item.${ac}`),
                    })),
                    attributes: f.attributes.map((a, attributeIndex) => ({
                        ...a,
                        onNameChange: (event) => {
                            const name = event.target.value
                            this.filters[filterIndex].attributes[attributeIndex].name = name

                            const defaultLabel = attributesInUrl[name]
                            this.filters[filterIndex].attributes[attributeIndex].label =
                                defaultLabel
                        },
                        onLabelInput: (event) => {
                            this.filters[filterIndex].attributes[attributeIndex].label =
                                event.target.value
                        },
                    })),
                }
            })
        },
    },
    render() {
        return this.$scopedSlots.default({
            filters: this.filtersOutput,
            remove: (filter) => {
                this.filters = this.filters.filter(({ _id }) => _id !== filter._id)
            },
            add: () => {
                this.filters.push({
                    _id: this.idCounter++,
                    url: null,
                    attributes: [
                        { name: null, label: null },
                        { name: null, label: null },
                        { name: null, label: null },
                    ],
                })
            },
        })
    },
}
</script>
