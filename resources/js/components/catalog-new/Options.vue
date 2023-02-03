<template>
    <div class="tw-flex tw-flex-col tw-flex-1 tw-min-w-full">
        <div class="tw-flex tw-border tw-border-gray-800 tw-mb-6">
            <input
                class="tw-px-2 tw-border-none focus:tw-ring-0 focus:tw-outline-none tw-placeholder-gray-800 tw-font-semibold tw-text-gray-800 tw-text-sm tw-py-1 tw-w-full"
                type="text"
                v-model="search"
                :placeholder="placeholder"
            />
            <div class="tw-flex tw-items-center tw-mr-2">
                <i class="fa fa-search"></i>
            </div>
        </div>
        <div
            class="tw-bg-white tw-flex-1 tw-flex tw-flex-col md:tw-max-h-80 md:tw-min-h-80 tw-pr-3 tw-scrollbar tw-scrollbar-w-1 tw-scrollbar-track-rounded tw-scrollbar-thumb-rounded tw-scrollbar-thumb-gray-600 tw-scrollbar-track-gray-200 tw-overflow-auto"
        >
            <label
                v-for="option in filteredOptions"
                :for="option.id"
                :class="[
                    'tw-flex tw-items-center',
                    {
                        'tw-bg-gray-200': option.checked,
                    },
                ]"
            >
                <input
                    class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-200 tw-m-2 tw-p-1 tw-h-4 tw-w-4"
                    type="checkbox"
                    :key="option.id"
                    :id="option.id"
                    @change="
                        (e) =>
                            handleMultiSelectChange(
                                filterName,
                                e.target.checked
                                    ? [...(selectedValues || []), option.name]
                                    : (selectedValues || []).filter(
                                          (selectedOption) => selectedOption !== option.name
                                      )
                            )
                    "
                    :checked="option.checked"
                />
                <span class="tw-font-normal tw-text-base"
                    >{{ option.name }} <span class="tw-font-semibold">({{ option.count }})</span>
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
        handleMultiSelectChange: Function,
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
                .filter((item) => (this.search ? item.name.includes(this.search) : true))
                .map((option) => ({
                    ...option,
                    checked: (this.selectedValues || []).includes(option.name),
                }))
        },
    },
}
</script>
