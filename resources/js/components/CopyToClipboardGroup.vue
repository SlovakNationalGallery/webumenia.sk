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
            <copy-to-clipboard-link
                :value="value"
                :successText="successText"
                class="btn btn-default btn-sm"
            >
                <i class="fa fa-clipboard"></i>
                {{ buttonLabel }}
            </copy-to-clipboard-link>
        </span>
    </div>
</template>

<script>
import CopyToClipboardLink from './CopyToClipboardLink'

export default {
    components: {
        "copy-to-clipboard-link": CopyToClipboardLink,
    },
    props: {
        value: String,
        buttonLabel: String,
        successText: String,
    },
}
</script>
