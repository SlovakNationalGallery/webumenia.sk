<div>
    @foreach ($filters as $filterIndex => $filter)
        <x-admin.label value="URL" />
        <x-admin.input
            placeholder="{{ LaravelLocalization::getLocalizedURL($locale, 'katalog/...') }}"
            name="{{ $locale }}[filters][{{ $filterIndex }}][url]'"
            wire:model="filters.{{ $filterIndex }}.url" />

        <x-admin.label class="tw-mt-4" value="Filtre" />
        <div class="tw-mt-2 tw-grid tw-grid-cols-3 tw-gap-x-4">
            @foreach ($filter['attributes'] as $attributeIndex => $attribute)
                <div class="tw-flex tw-flex-col tw-space-y-2">

                    <x-admin.select
                        name="{{ $locale }}[filters][{{ $filterIndex }}][attributes][{{ $attributeIndex }}][name]"
                        wire:model="filters.{{ $filterIndex }}.attributes.{{ $attributeIndex }}.name"
                        wire:change="fillLabelWithUrlValue({{ $filterIndex }}, {{ $attributeIndex }})">
                        <option>...</option>

                        @foreach ($this->getSelectableAttributes($filterIndex) as $sa)
                            <option value="{{ $sa }}">{{ trans("item.$sa") }}</option>
                        @endforeach
                    </x-admin.select>

                    <x-admin.input
                        name="{{ $locale }}[filters][{{ $filterIndex }}][attributes][{{ $attributeIndex }}][label]"
                        wire:model.lazy="filters.{{ $filterIndex }}.attributes.{{ $attributeIndex }}.label" />
                </div>
            @endforeach
        </div>
        <div class="tw-mt-2 tw-flex tw-flex-col tw-items-center tw-space-y-2">
            <x-admin.button type="button" sm outline wire:click="delete('{{ $filter['_id'] }}')">
                🗙 Zmazať
            </x-admin.button>
        </div>
    @endforeach

    <x-admin.button type="button" sm outline class="tw-mt-4" wire:click="add">
        + Pridať
    </x-admin.button>
</div>
