<script setup>
import { ref, computed } from 'vue'
import { trans } from 'laravel-vue-i18n'
import {
    SelectRoot,
    SelectTrigger,
    SelectPortal,
    SelectContent,
    SelectViewport,
    SelectItem,
    SelectItemText,
} from 'radix-vue'
import CaretUpIcon from './icons/CaretUp.vue'

const props = defineProps({
    defaultValue: String,
})

const value = ref(props.defaultValue ?? 'relevance')

const emit = defineEmits(['change'])

const options = [
    { value: 'created_at', label: trans('sortable.created_at') },
    { value: 'title', label: trans('sortable.title') },
    { value: 'author', label: trans('sortable.author') },
    { value: 'date_earliest', label: trans('sortable.oldest') },
    { value: 'date_latest', label: trans('sortable.newest') },
    { value: 'view_count', label: trans('sortable.view_count') },
    { value: 'random', label: trans('sortable.random') },
    { value: 'updated_at', label: trans('sortable.updated_at') },
    { value: 'relevance', label: trans('sortable.relevance') },
]

const label = computed(
    () => options.find((option) => option.value === value.value)?.label ?? value.value
)
</script>
<template>
    <SelectRoot v-model="value" @update:modelValue="emit('change', value)">
        <SelectTrigger
            class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4 tw-outline-none"
        >
            <transition
                enter-from-class="tw-opacity-0"
                leave-to-class="tw-opacity-0"
                enter-active-class="tw-duration-50 tw-transition"
                leave-active-class="tw-duration-50 tw-transition"
                mode="out-in"
            >
                <div class="tw-flex tw-items-center tw-space-x-1" :key="value">
                    <span>
                        {{ label }}
                    </span>
                    <CaretUpIcon class="tw-h-4 tw-w-4 tw-rotate-180" />
                </div>
            </transition>
        </SelectTrigger>

        <SelectPortal>
            <div class="tailwind-rules">
                <transition
                    enter-from-class="tw-opacity-0"
                    leave-to-class="tw-opacity-0"
                    enter-active-class="tw-transition-opacity tw-duration-100"
                    leave-active-class="tw-transition-opacity tw-duration-100"
                >
                    <SelectContent
                        position="popper"
                        class="tw-w-80 tw-border-2 tw-border-gray-800 tw-bg-white tw-p-4"
                    >
                        <SelectViewport>
                            <SelectItem
                                v-for="option in options"
                                v-show="option.value !== value"
                                :key="option.value"
                                :value="option.value"
                                class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200 tw-outline-none"
                            >
                                <SelectItemText>{{ option.label }}</SelectItemText>
                            </SelectItem>
                        </SelectViewport>
                    </SelectContent>
                </transition>
            </div>
        </SelectPortal>
    </SelectRoot>
</template>
