<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { matchSorter } from 'match-sorter'

import ResetButton from './ResetButton.vue'
import Count from './NumberFormatter.vue'
import EmptyAnimation from './EmptyAnimation.vue'

const props = defineProps<{
    options: { value: string; count: number }[]
    selected?: string[]
    searchPlaceholder: string
    formatLabel?: (value: string) => string
}>()

const emit = defineEmits(['change', 'reset'])

const allOptions = ref(props.options)
const search = ref('')

const selected = computed(() => props.selected ?? [])

const options = computed(() => {
    const formatLabel = props.formatLabel ?? ((value: string) => value)

    const optionsWithSelected = [
        ...allOptions.value.map((option) => ({
            ...option,
            checked: selected.value.includes(option.value),
            label: formatLabel(option.value),
        })),
        ...selected.value
            .filter((queryItem) => allOptions.value.every((option) => option.value !== queryItem))
            .map((value) => ({
                value,
                count: 0,
                checked: true,
                label: formatLabel(value),
            })),
    ]

    return search.value
        ? matchSorter(optionsWithSelected, search.value, {
              keys: ['label'],
          })
        : optionsWithSelected
})

onMounted(() => {
    // Pre-sort options if they're initially selected
    allOptions.value = props.options.toSorted((a, b) => {
        const aChecked = selected.value.includes(a.value)
        const bChecked = selected.value.includes(b.value)

        if (aChecked === bChecked) {
            return 0
        }
        if (aChecked) return -1
        return 1
    })
})
</script>

<template>
    <div class="tw-flex tw-w-full tw-flex-1 tw-flex-col">
        <div class="tw-mx-4 tw-mb-6 tw-flex tw-border tw-border-gray-800 md:tw-mx-0">
            <input
                class="tw-w-full tw-border-none tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-leading-none tw-text-gray-800 tw-placeholder-gray-800 focus:tw-outline-none focus:tw-ring-0"
                type="text"
                @input="search = $event.target.value"
                :value="search"
                :placeholder="props.searchPlaceholder"
            />
            <div class="tw-mr-3 tw-flex tw-items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="tw-h-4 tw-w-4 tw-fill-current"
                    viewBox="0 0 256 256"
                >
                    <path
                        d="M232.49,215.51,185,168a92.12,92.12,0,1,0-17,17l47.53,47.54a12,12,0,0,0,17-17ZM44,112a68,68,0,1,1,68,68A68.07,68.07,0,0,1,44,112Z"
                    ></path>
                </svg>
            </div>
        </div>
        <div
            class="md:tw-min-h-80 tw-flex tw-h-[calc(100vh-18rem)] tw-flex-col tw-overflow-auto tw-bg-white tw-scrollbar tw-scrollbar-track-gray-200 tw-scrollbar-thumb-gray-600 tw-scrollbar-track-rounded tw-scrollbar-thumb-rounded tw-scrollbar-w-1 md:tw-max-h-80 md:tw-px-0 md:tw-pr-3"
        >
            <label
                v-for="option in options"
                :for="option.id"
                :class="[
                    'tw-flex md:tw-px-2 tw-px-4 tw-my-0.5 tw-py-1 hover:tw-bg-gray-200 tw-cursor-pointer',
                    { 'tw-bg-gray-200': option.checked },
                ]"
            >
                <input
                    class="tw-form-checkbox tw-m-0 tw-mr-3 tw-h-6 tw-w-6 tw-border-gray-300 focus:tw-outline-none focus:tw-ring-0 focus:tw-ring-offset-0"
                    type="checkbox"
                    :key="option.id"
                    :id="option.id"
                    :value="option.value"
                    :checked="option.checked"
                    @change="emit('change', $event)"
                />
                <span class="tw-inline-block tw-min-w-0 tw-break-words tw-text-base tw-font-normal">
                    {{ option.label }}
                    <span class="tw-font-semibold"> (<Count :value="option.count" />) </span>
                </span>
            </label>
            <div
                v-show="options.length === 0"
                class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-4"
            >
                <EmptyAnimation />
                <span class="tw-text-center">{{ $t('item.filter.nothing_found') }}</span>
            </div>
        </div>
    </div>
    <ResetButton class="tw-mt-5 tw-hidden md:tw-flex" @click="emit('reset')">
        {{ $t('item.filter.clear') }}
    </ResetButton>
</template>
