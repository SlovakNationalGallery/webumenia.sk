<template>
    <div class="tw-min-w-max tw-flex tw-space-x-3 tw-flex-wrap">
        <slot v-if="controller.isExtendedOpen" class="tw-invisible md:tw-visible"/>
        <button
            @click="controller.toggleIsExtendedOpen"
            class="tw-border hover:tw-border-gray-800 tw-border-gray-300 tw-text-lg tw-font-bold tw-py-3.5 tw-px-4"
        >
        <div class="tw-flex">
            <div class="tw-pr-4 tw-flex tw-items-center">
                <i v-if="controller.isExtended" class="fa fa-minus" />
                <i v-else class="fa fa-sliders" />
            </div>
            <span>{{controller.isExtended ? 'skryť ďalšie filter' : 'všetky filtre'}}</span>
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
