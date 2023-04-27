@extends('layouts.admin')

@section('title')
    @parent
    - Náhodné diela
@endsection

@section('content')
    <div class="admin">
        <div class="tw-container tw-mx-auto tw-pt-12">

            @if (session('message'))
                <x-admin.alert info>
                    {{ session('message') }}
                </x-admin.alert>
            @endif

            <h1 class="tw-border-b tw-pb-3 tw-text-4xl">Náhodné diela</h1>

            <div class="mt-4 tw-overflow-hidden tw-rounded-md tw-border tw-shadow">
                <div class="tw-border-b tw-bg-gray-100 tw-px-4 tw-py-3">
                    <x-admin.button btn primary outline :link="route('shuffled-items.create')">
                        <i class="fa fa-plus"></i> Vytvoriť
                    </x-admin.button>
                </div>
                <div class="tw-px-4 tw-py-2">
                    <table class="tw-w-full">
                        <thead class="tw-border-b-2 tw-border-gray-300">
                            <tr>
                                <th class="tw-p-2 tw-text-right">ID</th>
                                <th class="tw-p-2">Dielo</th>
                                <th class="tw-p-2">Filtre</th>
                                <th class="tw-p-2">Vytvorené</th>
                                <th class="tw-p-2">Publikované</th>
                                <th class="tw-p-2">Akcie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shuffledItems as $si)
                                <tr class="tw-border-b">
                                    <td class="tw-p-2 tw-text-right tw-align-top">{{ $si->id }}
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        @if ($si->image)
                                            {{ $si->image->img()->attributes(['class' => 'tw-h-20 tw-w-auto tw-rounded-md tw-object-contain']) }}
                                        @endif
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        <table class="tw-min-w-full">
                                            <tbody class="tw-bg-white">
                                                @foreach (config('translatable.locales') as $locale)
                                                    @if (optional($si->translate($locale))->filters)
                                                        <tr class="tw-border-b tw-border-gray-200">
                                                            <th colspan="3" scope="colgroup"
                                                                class="tw-px-2 tw-py-1 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-300">
                                                                {{ Str::upper($locale) }}</th>
                                                        </tr>
                                                        @foreach ($si->{"filters:$locale"} as $filter)
                                                            <tr class="tw-border-t tw-border-gray-300">
                                                                @foreach ($filter['attributes'] as $attribute)
                                                                    <td
                                                                        class="tw-whitespace-nowrap tw-py-2 tw-px-2 tw-text-xs tw-font-medium tw-text-gray-900">
                                                                        <span class="tw-font-semibold">
                                                                            {{ trans('item.' . $attribute['name'], [], $locale) }}</span>
                                                                        <br />{{ $attribute['label'] }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        @datetime($si->created_at)
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        @if ($si->is_published)
                                            <i class="fa fa-check tw-text-green-800"></i>
                                            @datetime($si->published_at)
                                        @endif
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        <div class="tw-flex tw-gap-1">
                                            <x-admin.button outline primary sm :link="route('shuffled-items.edit', $si)">
                                                Upraviť
                                            </x-admin.button>
                                            <x-admin.button outline success sm :link="route('home', ['shuffleItemId' => $si->id])">
                                                Na webe
                                            </x-admin.button>
                                            <x-admin.link-with-confirmation
                                                action="{{ route('shuffled-items.destroy', $si) }}"
                                                method="DELETE"
                                                class="tw-inline-block tw-rounded tw-border tw-border-[#d43f3a] tw-py-1 tw-px-2 tw-text-xs tw-font-medium tw-text-[#d9534f] hover:tw-border-[#ac2925] hover:tw-bg-[#d2322d] hover:tw-text-white active:tw-shadow-inner"
                                                message="Naozaj to chceš zmazať?">
                                                Zmazať
                                            </x-admin.link-with-confirmation>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
