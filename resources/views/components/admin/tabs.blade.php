@props(['tabs' => []])

<tabs-controller v-cloak v-slot="{ activeIndex }">
    <div class="tw-flex tw-space-x-1">
        @foreach ($tabs as $tab)
            <tab v-slot="{ active }">
                <button type="button"
                    :class="['tw--mb-px tw-rounded-t tw-py-2.5 tw-px-4 tw-border', active ? 'tw-border-b-white tw-border-b-[2px] tw-border-gray-300' : 'hover:tw-bg-gray-100 tw-border-transparent']">{{ $tab }}</button>
            </tab>
        @endforeach
    </div>
    <div
        :class="['p-4 tw--mb-px tw-rounded tw-border tw-border-gray-300', activeIndex === 0 && 'tw-rounded-tl-none']">
        @foreach ($tabs as $index => $tab)
            <tab-panel>
                {{ ${"tab_$index"} }}
            </tab-panel>
        @endforeach
    </div>
</tabs-controller>
