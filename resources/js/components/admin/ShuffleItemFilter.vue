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

export default {
    props: {
        defaultValue: Object,
    },
    data() {
        const defaultValue = this.defaultValue || {}
        return {
            url: defaultValue.url || null,
            selectedAttributes: defaultValue.selectedAttributes || [null, null, null],
        }
    },
    computed: {
        defaults() {
            try {
                const searchParams = new URL(this.url).searchParams
                const defaults = {}

                RecognizedAttributes.forEach((attribute) => {
                    const value = searchParams.get(attribute)
                    if (value) defaults[attribute] = value
                })

                return defaults
            } catch (error) {
                return {}
            }
        },
    },
    render() {
        return this.$scopedSlots.default({
            url: {
                value: this.url,
                onChange: (e) => (this.url = e.target.value),
            },
            selectableAttributes: Object.keys(this.defaults).map((attribute) => ({
                value: attribute,
                label: this.__(`item.${attribute}`),
            })),
            attributes: this.selectedAttributes.map((_, index) => ({
                onSelect: (e) => {
                    this.selectedAttributes[index] = e.target.value
                    // Force re-computation
                    this.selectedAttributes = [...this.selectedAttributes]
                },
                defaultValue: this.defaults[this.selectedAttributes[index]],
            })),
        })
    },
}
</script>
