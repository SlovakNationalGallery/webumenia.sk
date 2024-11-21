<script setup>
import { ref } from 'vue'
import { Dropdown as VDropdown } from 'floating-vue'

import CaretUpIcon from './icons/CaretUp.vue'

const props = defineProps({
    label: String,
    isActive: Boolean,
})

const isOpen = ref(false)
</script>

<template>
    <VDropdown
        :triggers="['click']"
        :shown="isOpen"
        :distance="10"
        placement="bottom-start"
        @show="isOpen = true"
        @hide="isOpen = false"
        :delay="0"
    >
        <div class="tw-border" :class="[isOpen ? 'tw-border-gray-800' : 'tw-border-transparent']">
            <button
                class="tw-border tw-bg-white tw-py-2.5 tw-px-4 hover:tw-border-gray-800"
                :class="[isOpen || isActive ? 'tw-border-gray-800' : 'tw-border-gray-300']"
            >
                <div class="tw-flex tw-items-center tw-space-x-4">
                    <slot name="trigger-label"></slot>
                    <CaretUpIcon
                        class="tw-h-4 tw-w-4"
                        :class="[isOpen ? 'tw-fill-sky-300' : 'tw-rotate-180']"
                    />
                </div>
            </button>
        </div>
        <template #popper>
            <div class="tailwind-rules">
                <div class="tw-hidden md:tw-block tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-mr-8">
                    <slot name="content"></slot>
                </div>
            </div>
        </template>
    </VDropdown>
</template>
