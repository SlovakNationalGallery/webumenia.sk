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
            class="tw-mr-1.5 tw-h-4 tw-w-4 tw-fill-current"
            viewBox="0 0 14 14"
        >
            <g clip-path="url(#a)">
                <path
                    d="M.885.152a.519.519 0 1 0-.733.733l12.963 12.963a.519.519 0 1 0 .733-.733L.885.152ZM4.681 1.919a.54.54 0 0 1 .515.706l.688.686a1.47 1.47 0 0 0 .196-.385h6.66a.467.467 0 1 0 0-.933H6.08a1.475 1.475 0 0 0-2.254-.734l.68.688a.54.54 0 0 1 .175-.028Z"
                />
                <path
                    fill-rule="evenodd"
                    d="M10.755 5.015c.651 0 1.204.422 1.4 1.007h.586a.467.467 0 1 1 0 .934h-.587a1.475 1.475 0 0 1-1.643.987L9.3 6.733V6.73l-.709-.707h.765a1.475 1.475 0 0 1 1.399-1.007Zm-.54 1.474a.54.54 0 1 1 1.081 0 .54.54 0 0 1-1.081 0ZM5.66 12.496c.65 0 1.203-.422 1.398-1.007h1.871l-.934-.933h-.937a1.475 1.475 0 0 0-2.798 0h-2.6a.467.467 0 1 0 0 .933h2.6a1.475 1.475 0 0 0 1.4 1.007Zm0-2.014a.54.54 0 1 0 0 1.081.54.54 0 0 0 0-1.082Z"
                    clip-rule="evenodd"
                />
                <path d="M1.66 6.022a.467.467 0 1 0 0 .934H4.39l-.955-.934H1.66Z" />
            </g>
            <defs>
                <clipPath id="a"><path d="M0 0h14v14H0z" /></clipPath>
            </defs>
        </svg>
        <slot></slot>
    </button>
</template>
