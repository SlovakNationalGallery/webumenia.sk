<template>
    <div class="tw-min-w-max">
        <button
            @click="toggleIsExtendedOpen"
            class="tw-border tw-w-full tw-justify-center hover:tw-border-gray-800 tw-border-gray-300 tw-text-lg tw-font-bold tw-py-2.5 tw-px-4 tw-flex tw-items-center"
        >
            <div class="tw-pr-4 tw-flex tw-items-center">
                <slot v-if="isExtendedOpen" name="more-icon"> </slot>
                <slot v-else name="less-icon"> </slot>
            </div>
            <span class="tw-font-semibold">{{
                isExtendedOpen ? 'skryť ďalšie filtre' : 'všetky filtre'
            }}</span>
        </button>
    </div>
</template>

<script>
export default {
    props: { isExtendedOpen: Boolean, toggleIsExtendedOpen: Function },
}
</script>
