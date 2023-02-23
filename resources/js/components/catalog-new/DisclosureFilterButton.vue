<template>
    <div class="tw-min-w-max">
        <button
            class="tw-border tw-border-gray-300 tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
            @click="$emit('click')"
        >
            <div class="tw-flex">
                <slot></slot>
                <div class="tw-flex tw-items-center tw-pl-4">
                    <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </button>
    </div>
</template>
