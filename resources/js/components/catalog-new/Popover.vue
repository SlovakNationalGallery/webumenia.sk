<script setup>
import { onMounted, ref } from 'vue'
import { PopoverRoot, PopoverTrigger, PopoverPortal, PopoverContent } from 'radix-vue'

import CaretUpIcon from './icons/CaretUp.vue'

const props = defineProps({
    label: String,
    isActive: Boolean,
})

const isOpen = ref(false)
</script>

<template>
    <PopoverRoot @update:open="($open) => (isOpen = $open)">
        <div class="tw-border" :class="[isOpen ? 'tw-border-gray-800' : 'tw-border-transparent']">
            <PopoverTrigger
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
            </PopoverTrigger>
        </div>

        <PopoverPortal>
            <!-- PopoverPortal teleports outside of .tailwind-rules scope -->
            <div class="tailwind-rules">
                <Transition
                    class="tw-origin-[var(--radix-popover-content-transform-origin)]"
                    enter-from-class="tw-opacity-0"
                    leave-to-class="tw-opacity-0"
                    enter-active-class="tw-transition-opacity tw-duration-100"
                    leave-active-class="tw-transition-opacity tw-duration-100"
                >
                    <PopoverContent
                        side="bottom"
                        align="start"
                        :side-offset="10"
                        class="tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-mr-8 -tw-ml-px"
                    >
                        <slot name="content"></slot>
                    </PopoverContent>
                </Transition>
            </div>
        </PopoverPortal>
    </PopoverRoot>
</template>
