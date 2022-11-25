<template>
    <div class="tw-min-w-max">
        <button
            @click="controller.toggleIsExtendedOpen"
            class="tw-border tw-w-full hover:tw-border-gray-800 tw-border-gray-300 tw-text-lg tw-font-bold tw-py-3.5 tw-px-4"
        >
            <div class="tw-flex tw-justify-center">
                <div class="tw-pr-4 tw-flex tw-items-center">
                    <i v-if="controller.isExtended" class="fa fa-minus"></i>
                    <i v-else class="fa fa-sliders"></i>
                </div>
                <span>{{ controller.isExtendedOpen ? 'skryť ďalšie filtre' : 'všetky filtre' }}</span>
            </div>
        </button>
    </div>
</template>

<script>
export default {
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
