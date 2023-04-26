<template>
    <div class="input-group">
        <input
            ref="input"
            type="text"
            readonly
            class="form-control input-sm tw-bg-white"
            :value="value"
            v-on:focus="(e) => e.target.select()"
        />
        <span class="input-group-btn">
            <copy-to-clipboard-link :value="value" class="btn btn-dark btn-sm tw-font-light">
                {{ __('general.copy_link') }}
                <i class="fa fa-link ml-2"></i>
            </copy-to-clipboard-link>
        </span>
    </div>
</template>

<script>
import CopyToClipboardLink from './CopyToClipboardLink'

export default {
    components: {
        'copy-to-clipboard-link': CopyToClipboardLink,
    },
    props: {
        value: String,
    },
}
</script>
