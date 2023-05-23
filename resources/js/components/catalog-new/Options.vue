<template>
    <div class="tw-flex tw-flex-col tw-flex-1 tw-min-w-full">
        <div class="tw-flex tw-border tw-border-gray-800 tw-mb-6 tw-mx-4 md:tw-mx-0">
            <input
                class="tw-px-4 tw-border-none tw-leading-none focus:tw-ring-0 focus:tw-outline-none tw-placeholder-gray-800 tw-font-semibold tw-text-gray-800 tw-text-sm tw-py-2 tw-w-full"
                type="text"
                v-model="search"
                :placeholder="placeholder"
            />
            <div class="tw-flex tw-items-center tw-mr-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="tw-w-4 tw-h-4 tw-fill-current"
                    viewBox="0 0 256 256"
                >
                    <path
                        d="M232.49,215.51,185,168a92.12,92.12,0,1,0-17,17l47.53,47.54a12,12,0,0,0,17-17ZM44,112a68,68,0,1,1,68,68A68.07,68.07,0,0,1,44,112Z"
                    ></path>
                </svg>
            </div>
        </div>
        <div
            class="tw-bg-white tw-h-[calc(100vh-15rem)] tw-flex tw-flex-col md:tw-max-h-80 md:tw-min-h-80 md:tw-px-0 md:tw-pr-3 tw-scrollbar tw-scrollbar-w-1 tw-scrollbar-track-rounded tw-scrollbar-thumb-rounded tw-scrollbar-thumb-gray-600 tw-scrollbar-track-gray-200 tw-overflow-auto"
        >
            <label
                v-for="option in filteredOptions"
                :for="option.id"
                :class="[
                    'tw-flex md:tw-px-2 tw-px-4 tw-my-2',
                    {
                        'tw-bg-gray-200': option.checked,
                    },
                ]"
            >
                <input
                    class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-300 tw-m-0 tw-mr-3 tw-h-6 tw-w-6"
                    type="checkbox"
                    :key="option.id"
                    :id="option.id"
                    @change="$emit('change', $event)"
                    :value="option.value"
                    :name="filterName"
                    :checked="option.checked"
                />
                <span class="tw-font-normal tw-text-base"
                    >{{ option.value }} <span class="tw-font-semibold">({{ option.count }})</span>
                </span>
            </label>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        filterName: String,
        placeholder: String,
        selectedValues: Array,
        filter: Array,
    },
    data() {
        return {
            search: '',
        }
    },
    computed: {
        filteredOptions() {
            return this.filter
                .filter((item) => (this.search ? item.value.includes(this.search) : true))
                .map((option) => ({
                    ...option,
                    checked: (this.selectedValues || []).includes(option.value),
                }))
        },
    },
}
</script>
