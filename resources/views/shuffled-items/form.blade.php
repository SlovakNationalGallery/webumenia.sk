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
                        <div class="tw-grid tw-grid-cols-3 tw-gap-x-4">
                            <div class="tw-flex tw-flex-col tw-space-y-2">
                                <x-admin.select name="type">
                                    <option value="volvo">Volvo</option>
                                    <option value="saab">Saab</option>
                                    <option value="mercedes">Mercedes</option>
                                    <option value="audi">Audi</option>
                                </x-admin.select>
                                <x-admin.select name="hodnota">
                                    <option value="volvo">Volvo</option>
                                    <option value="saab">Saab</option>
                                    <option value="mercedes">Mercedes</option>
                                    <option value="audi">Audi</option>
                                </x-admin.select>
                                <x-admin.input placeholder="label" />
                            </div>
                            <div
                                class="tw-flex tw-items-center tw-justify-center tw-rounded tw-border tw-py-14">
                                <x-admin.button sm outline>+ Pridať</x-admin.button>
                            </div>
                            <div class="tw-rounded tw-border">&nbsp;</div>
                        </div>
                        <div class="tw-mt-4 tw-flex tw-flex-col tw-items-center tw-space-y-2">
                            <span class="tw-text-xs">13 výsledkov</span>
                            <x-admin.button sm outline>🗙 Zmazať</x-admin.button>
                        </div>

                        <div class="tw-mt-8">
                            <x-admin.checkbox id="is_published" name="is_published"
                                :checked="old('is_published', $shuffledItem->is_published)" />
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
