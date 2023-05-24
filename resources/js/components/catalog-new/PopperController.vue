<script>
import { createPopper } from '@popperjs/core'
export default {
    props: {
        name: String,
    },
    data() {
        return {
            popper: null,
        }
    },
    mounted() {
        const button = document.getElementById(`button-${this.name}`)
        const body = document.getElementById(`body-${this.name}`)
        this.popper = createPopper(button, body, {
            placement: 'bottom-start',
        })
    },
    updated() {
        this.popper.update()
    },
    render() {
        return this.$scopedSlots.default({})
    },
}
</script>
