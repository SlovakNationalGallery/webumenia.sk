<template>
    <div class="input-group">
        <input
            ref="input"
            type="text"
            readonly
            class="form-control input-sm bg-light"
            :value="value"
            v-on:focus="e => e.target.select()"
        >
        <span class="input-group-btn">
            <button
                ref="button"
                type="button"
                class="btn btn-default btn-sm"
                :title="successText"
                @click="copy"
            >
                <i class="fa fa-clipboard"></i>
                {{ buttonLabel }}
            </button>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        value: String,
        buttonLabel: String,
        successText: String,
    },
    methods: {
        tooltip(command) {
            $(this.$refs.button).tooltip(command);
        },
        copy(e) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(this.value)
            } else {
                this.$refs.input.select()
                document.execCommand("copy")
            }

            this.tooltip('show')

            setTimeout(() => {
                this.tooltip('hide')
            }, 2000)
        },
    },
    mounted() {
        this.tooltip({
            trigger: 'manual',
            container: 'body'
        })
    },
    beforeDestroy() {
        this.tooltip('destroy')
    }
}
</script>
