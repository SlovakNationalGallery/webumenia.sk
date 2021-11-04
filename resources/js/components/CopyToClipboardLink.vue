<template>
    <a ref="link" href="#" @click.prevent.stop="copy">
        <slot></slot>
    </a>
</template>

<script>
export default {
    props: {
        value: String,
        successText: String,
    },
    methods: {
        tooltip(command) {
            $(this.$refs.link).tooltip(command);
        },
        copy() {
            navigator.clipboard.writeText(this.value)

            this.tooltip('show')

            setTimeout(() => {
                this.tooltip('hide')
            }, 2000)
        },
    },
    mounted() {
        this.tooltip({
            trigger: 'manual',
            container: 'body',
            title: this.successText,
        })
    },
    beforeDestroy() {
        this.tooltip('destroy')
    }
}
</script>
