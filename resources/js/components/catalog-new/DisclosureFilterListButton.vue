<template>
    <button
        class="tw-border-b tw-flex tw-justify-between tw-items-center tw-w-full tw-border-gray-300 tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
        @click="$emit('click')"
    >
        <slot></slot>
        <svg
            class="tw-ml-4 tw-h-4 tw-w-4 tw-fill-current"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 256 256"
        >
            <path
                d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z"
            ></path>
        </svg>
    </button>
</template>
