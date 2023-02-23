<template>
    <button
        class="tw-border-b tw-w-full tw-border-gray-300 tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
        @click="$emit('click')"
    >
        <div class="tw-flex">
            <slot></slot>
            <div class="tw-flex tw-ml-auto tw-items-center tw-pl-4">
                <i class="fa fa-caret-right"></i>
            </div>
        </div>
    </button>
</template>
