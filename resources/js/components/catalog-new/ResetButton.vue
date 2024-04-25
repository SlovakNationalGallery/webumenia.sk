<script setup lang="ts">
const props = defineProps<{
    sm?: boolean
}>()
</script>
<template>
    <button
        :class="[
            'tw-flex tw-items-center tw-border tw-leading-none tw-border-gray-300 tw-bg-white tw-font-normal hover:tw-border-gray-800',
            props.sm
                ? 'tw-px-1.5 tw-py-1 tw-text-xs'
                : 'tw-px-1.5 tw-py-1 tw-text-xs md:tw-px-4 md:tw-py-1.5 md:tw-text-sm',
        ]"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 256 256"
            class="tw-mr-1.5 tw-h-4 tw-w-4 tw-fill-current"
        >
            <path
                d="M228,128a100,100,0,0,1-98.66,100H128a99.39,99.39,0,0,1-68.62-27.29,12,12,0,0,1,16.48-17.45,76,76,0,1,0-1.57-109c-.13.13-.25.25-.39.37L54.89,92H72a12,12,0,0,1,0,24H24a12,12,0,0,1-12-12V56a12,12,0,0,1,24,0V76.72L57.48,57.06A100,100,0,0,1,228,128Z"
            ></path>
        </svg>
        <slot></slot>
    </button>
</template>
