<filter-search-options-controller {{ $attributes->only(['v-bind:options', 'v-bind:selected']) }}
    v-slot="sc">
    <div class="tw-flex tw-w-full tw-flex-1 tw-flex-col">
        <div class="tw-mx-4 tw-mb-6 tw-flex tw-border tw-border-gray-800 md:tw-mx-0">
            <input
                class="tw-w-full tw-border-none tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-leading-none tw-text-gray-800 tw-placeholder-gray-800 focus:tw-outline-none focus:tw-ring-0"
                type="text" v-on:input="sc.onSearchInput" v-bind:value="sc.search"
                placeholder="{{ $attributes->get('search-placeholder') }}" />
            <div class="tw-mr-3 tw-flex tw-items-center">
                <x-icons.magnifying-glass class="tw-h-4 tw-w-4 tw-fill-current" />
            </div>
        </div>
        <div
            class="md:tw-min-h-80 tw-flex tw-h-[calc(100vh-18rem)] tw-flex-col tw-overflow-auto tw-bg-white tw-scrollbar tw-scrollbar-track-gray-200 tw-scrollbar-thumb-gray-600 tw-scrollbar-track-rounded tw-scrollbar-thumb-rounded tw-scrollbar-w-1 md:tw-max-h-80 md:tw-px-0 md:tw-pr-3">
            <label v-if="sc.options.length > 0" v-for="option in sc.options" v-bind:for="option.id"
                v-bind:class="['tw-flex md:tw-px-2 tw-px-4 tw-my-0.5 tw-py-1 hover:tw-bg-gray-200 tw-cursor-pointer', { 'tw-bg-gray-200': option.checked }]">
                <input
                    class="tw-form-checkbox tw-m-0 tw-mr-3 tw-h-6 tw-w-6 tw-border-gray-300 focus:tw-outline-none focus:tw-ring-0 focus:tw-ring-offset-0"
                    type="checkbox" v-bind:key="option.id" v-bind:id="option.id"
                    v-bind:value="option.value" v-bind:checked="option.checked"
                    @change="{{ $attributes->get('v-on:change') }}" />
                <span class="tw-inline-block tw-min-w-0 tw-break-words tw-text-base tw-font-normal">
                    {!! $label ?? '@{{ option.value }}' !!}
                    <span class="tw-font-semibold">
                        (<catalog.number-formatter v-bind:value="option.count">
                        </catalog.number-formatter>)
                    </span>
                </span>
            </label>
            <div v-else class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-4">
                <lottie-player autoplay loop mode="normal"
                    src="{{ asset('animations/empty.json') }}">
                </lottie-player>
                <span class="tw-text-center">Uuups, nič sme nenašli :(</span>
            </div>
        </div>
    </div>
</filter-search-options-controller>
<x-filter.reset_button class="tw-mb-6 tw-mt-5 tw-hidden md:tw-flex"
    @click="{{ $attributes->get('v-on:reset') }}">
    {{ trans('item.filter.clear') }}
</x-filter.reset_button>
