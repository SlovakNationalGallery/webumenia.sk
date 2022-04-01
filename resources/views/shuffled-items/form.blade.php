@extends('layouts.admin')

@section('content')
    <div class="tailwind-rules admin">
        <div class="mx-auto tw-container tw-max-w-screen-md tw-pt-12">
            @if ($errors->any())
                <x-admin.alert danger>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-admin.alert>
            @endif

            <x-admin.form :model="$shuffledItem" files>
                <x-admin.label value="Dielo" />
                <query-string v-slot="qs" class="sm:tw-w-2/3">
                    <autocomplete v-bind:remote="{ url: '/katalog/suggestions?search=%QUERY' }"
                        placeholder="Zadaj názov, autora, ..." value="{{ request('itemId') }}"
                        v-on:input="({id}) => qs.set('itemId', id)" option-label="id">
                        <template v-slot:option="option">
                            <div class="tw-flex">
                                <img :src="option.image" class="tw-h-16 tw-w-16 tw-object-cover">
                                <div class="tw-max-w-full tw-truncate tw-px-4 tw-py-2">
                                    <h4 class="tw-font-semibold">@{{ option.title }}</h4>
                                    <span>@{{ option.author }} ∙ @{{ option.id }}</span>
                                </div>
                            </div>
                        </template>
                    </autocomplete>
                </query-string>

                @if ($shuffledItem->item)
                    <div class="tw-mt-2 tw-flex tw-rounded-md tw-border tw-shadow md:tw-w-2/3">
                        <a href="{{ route('dielo', $shuffledItem->item) }}"
                            class="tw-inline-block tw-h-32 tw-w-1/3">
                            <img src="{{ $shuffledItem->item->getImagePath() }}"
                                class="tw-h-full tw-w-full tw-rounded-tl-md tw-rounded-bl-md tw-object-cover" />
                        </a>
                        <div class="tw-col-span-2 tw-space-y-1 tw-py-2 tw-px-4">
                            <h2 class="tw-text-lg hover:tw-underline">
                                <a href="{{ route('dielo', $shuffledItem->item) }}">
                                    {{ $shuffledItem->title }}
                                </a>
                            </h2>
                            <div>
                                @foreach ($shuffledItem->authorLinks as $a)
                                    <x-admin.link href="{{ $a->url }}">
                                        {{ $a->label }}
                                    </x-admin.link>{{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </div>
                            <div>{{ $shuffledItem->dating_formatted }}</div>
                        </div>

                        <input type="hidden" name="item_id" value="{{ $shuffledItem->item->id }}" />
                    </div>

                    <div class="tw-mt-8">
                        <x-admin.label for="image" value="Obrázok" />
                        <croppr src="{{ $shuffledItem->item->getImagePath() }}"
                            class="md:tw-w-2/3"
                            :default-value="{{ Js::from($shuffledItem->crop) }}"
                            :aspect-ratio=" 16/9" v-slot="{ value }">
                            <input type="hidden" name="crop" :value="JSON.stringify(value)" />
                        </croppr>
                    </div>

                    <div class="tw-mt-8">
                        <x-admin.label value="Filtre" />
                        <x-admin.tabs
                            :tabs="collect(config('translatable.locales'))->map(fn ($l) => Str::upper($l))">
                            @foreach (config('translatable.locales') as $tabIndex => $locale)
                                <x-slot :name='"tab_$tabIndex"'>
                                    <list-manager v-slot="list"
                                        v-bind:value="{{ Js::from($shuffledItem->translateOrNew($locale)->filters ?? []) }}">
                                        <div>
                                            <admin.shuffle-item-filter
                                                v-for=" (filter, filterIndex) in list.items"
                                                v-bind:key="filter.id" v-bind:value="filter"
                                                v-slot="{ url, selectableAttributes, attributes }">
                                                <div>
                                                    <x-admin.label value="URL" />
                                                    <x-admin.input
                                                        placeholder="{{ LaravelLocalization::getLocalizedURL($locale, 'katalog/...') }}"
                                                        v-bind:name="'{{ $locale }}[filters][' + filterIndex + '][url]'"
                                                        v-bind:value="url.value"
                                                        v-on:input="url.onChange" />

                                                    <x-admin.label class="tw-mt-4"
                                                        value="Filtre" />
                                                    <div
                                                        class="tw-mt-2 tw-grid tw-grid-cols-3 tw-gap-x-4">
                                                        <div v-for="(attribute, attributeIndex) in attributes"
                                                            v-bind:key="attributeIndex"
                                                            class="tw-flex tw-flex-col tw-space-y-2">
                                                            <x-admin.select
                                                                v-bind:name="'{{ $locale }}[filters][' + filterIndex + '][attributes][' + attributeIndex + '][name]'"
                                                                v-bind:value="attribute.name"
                                                                v-on:input="attribute.onSelect">
                                                                <option>...</option>
                                                                <option
                                                                    v-for="sa in selectableAttributes"
                                                                    v-bind:key="sa.value"
                                                                    v-bind:value="sa.value">
                                                                    @{{ sa.label }}
                                                                </option>
                                                            </x-admin.select>
                                                            <x-admin.input
                                                                v-bind:value="attribute.value || attribute.defaultValue"
                                                                v-bind:name="'{{ $locale }}[filters][' + filterIndex + '][attributes][' + attributeIndex + '][label]'" />
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="tw-mt-2 tw-flex tw-flex-col tw-items-center tw-space-y-2">
                                                        <x-admin.button type="button" sm outline
                                                            v-on:click="list.delete(filterIndex)">
                                                            🗙 Zmazať
                                                        </x-admin.button>
                                                    </div>
                                                </div>
                                            </admin.shuffle-item-filter>

                                            <x-admin.button type="button" sm outline
                                                class="tw-mt-4" v-on:click="list.push()">
                                                + Pridať
                                            </x-admin.button>
                                        </div>
                                    </list-manager>
                                </x-slot>
                            @endforeach
                        </x-admin.tabs>

                        <div class="tw-mt-8">
                            <x-admin.checkbox id="is_published" name="is_published"
                                :checked="old(' is_published', $shuffledItem->is_published)" />
                            <label for="is_published"
                                class="tw-ml-1 tw-select-none tw-font-normal">Publikovať</label>
                            @if ($shuffledItem->is_published)
                                <p class="tw-text-gray-400">Publikované
                                    {{ $shuffledItem->published_at }}
                                </p>
                            @endif
                        </div>

                        <div class="tw-mt-8 tw-text-center">
                            <x-admin.button primary>
                                Uložiť
                            </x-admin.button>
                            <x-admin.button :link="route('shuffled-items.index')">
                                Zrušiť
                            </x-admin.button>
                        </div>
                    </div>
                @endif
            </x-admin.form>
        </div>
    </div>
@endsection

