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
                data-toggle="tooltip"
                data-placement="top"
                data-trigger="manual"
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
        copy(e) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(this.value)
            } else {
                this.$refs.input.select()
                document.execCommand("copy")
            }

            $(this.$refs.button).tooltip('show')

            setTimeout(() => {
                $(this.$refs.button).tooltip('hide')
            }, 3000)
        },
    }
}
</script>
