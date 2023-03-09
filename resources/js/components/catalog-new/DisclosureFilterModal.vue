<template>
    <div
        class="tw-fixed tw-right-0 tw-top-0 tw-z-30 tw-flex tw-h-full tw-w-full tw-flex-col md:tw-hidden"
    >
        <button
            @click="$emit('close')"
            class="tw-left-0 tw-h-10 tw-min-w-full tw-shrink-0 tw-bg-gray-800 tw-opacity-40"
        ></button>
        <slot name="body"></slot>
        <div
            class="tw-flex tw-w-full tw-bg-white tw-pb-6 tw-shadow-lg tw-drop-shadow-[0_-2px_13px_rgba(0,0,0,0.1)]"
        >
            <slot name="footer"></slot>
        </div>
    </div>
</template>
