<template>
    <div class="input-group">
        <input
            ref="input"
            type="text"
            readonly
            class="form-control input-sm bg-light"
            :value="value"
            v-on:focus="(e) => e.target.select()"
        />
        <span class="input-group-btn">
            <copy-to-clipboard-link :value="value" class="btn btn-dark btn-sm font-light">
                {{ $t('general.copy_link') }}
                <i class="fa fa-link ml-2"></i>
            </copy-to-clipboard-link>
        </span>
    </div>
</template>

<script>
import CopyToClipboardLink from './CopyToClipboardLink.vue'

export default {
    components: {
        'copy-to-clipboard-link': CopyToClipboardLink,
    },
    props: {
        value: String,
    },
}
</script>
