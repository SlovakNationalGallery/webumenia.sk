<filter-search-options-controller {{ $attributes->only(['v-bind:options', 'v-bind:selected']) }} v-slot="sc">
    <div class="tw-flex tw-flex-col tw-flex-1 tw-w-full">
        <div class="tw-flex tw-border tw-border-gray-800 tw-mb-6 tw-mx-4 md:tw-mx-0">
            <input
                class="tw-px-4 tw-border-none tw-leading-none focus:tw-ring-0 focus:tw-outline-none tw-placeholder-gray-800 tw-font-semibold tw-text-gray-800 tw-text-sm tw-py-2 tw-w-full"
                type="text"
                v-on:input="sc.onSearchInput"
                :value="sc.search"
                placeholder="{{ $attributes->get('search-placeholder') }}"
            />
            <div class="tw-flex tw-items-center tw-mr-3">
                <x-icons.magnifying-glass class="tw-h-4 tw-w-4 tw-fill-current" />
            </div>
        </div>
        <div
            class="tw-bg-white tw-h-[calc(100vh-18rem)] tw-flex tw-flex-col md:tw-max-h-80 md:tw-min-h-80 md:tw-px-0 md:tw-pr-3 tw-scrollbar tw-scrollbar-w-1 tw-scrollbar-track-rounded tw-scrollbar-thumb-rounded tw-scrollbar-thumb-gray-600 tw-scrollbar-track-gray-200 tw-overflow-auto"
        >
            <label
                v-for="option in sc.options"
                :for="option.id"
                :class="[
                    'tw-flex md:tw-px-2 tw-px-4 tw-my-0.5 tw-py-1 hover:tw-bg-gray-200',
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
                    :value="option.value"
                    :checked="option.checked"
                    @change="{{ $attributes->get('v-on:change') }}"
                />
                <span class="tw-font-normal tw-inline-block tw-text-base tw-break-words tw-min-w-0"
                    >@{{ option.value }} <span class="tw-font-semibold">(@{{ option.count }})</span>
                </span>
            </label>
        </div>
    </div>
</filter-search-options-controller>
<x-filter.reset_button class="tw-hidden md:tw-flex tw-mb-6 tw-mt-5" @click="{{ $attributes->get('v-on:reset') }}">
    zrušiť výber
</x-filter.reset_button>