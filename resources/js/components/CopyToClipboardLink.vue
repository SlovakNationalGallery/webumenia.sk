<template>
    <a ref="link" href="#" @click.prevent.stop="copy">
        <slot></slot>
    </a>
</template>

<script>
import { trans } from 'laravel-vue-i18n'

export default {
    props: {
        value: String,
    },
    methods: {
        tooltip(command) {
            $(this.$refs.link).tooltip(command)
        },
        copy() {
            navigator.clipboard.writeText(this.value)

            this.tooltip({
                trigger: 'manual',
                container: 'body',
                title: trans('general.copied_to_clipboard'),
            })

            this.tooltip('show')

            setTimeout(() => {
                this.tooltip('hide')
                this.tooltip('destroy')
            }, 2000)
        },
    },
}
</script>
