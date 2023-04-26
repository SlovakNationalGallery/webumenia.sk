@extends('layouts.admin')

@section('title')
    @parent
    - Vybrané diela
@endsection

@section('content')
    <div class="tailwind-rules admin">
        <div class="tw-container tw-mx-auto tw-pt-12">

            @if (session('message'))
                <x-admin.alert info>
                    {{ session('message') }}
                </x-admin.alert>
            @endif

            <h1 class="tw-text-4xl tw-border-b tw-pb-3">Vybrané diela</h1>

            <div class="mt-4 tw-rounded-md tw-overflow-hidden tw-border tw-shadow">
                <div class="tw-px-4 tw-py-3 tw-bg-gray-100 tw-border-b">
                    <x-admin.button btn primary outline :link="route('featured-artworks.create')">
                        <i class="fa fa-plus"></i> Vytvoriť
                    </x-admin.button>
                </div>
                <div class="tw-px-4 tw-py-2">
                    <table class="tw-w-full">
                        <thead class="tw-border-b-2 tw-border-gray-300">
                            <tr>
                                <th class="tw-p-2 tw-text-right">ID</th>
                                <th class="tw-p-2">Dielo</th>
                                <th class="tw-p-2">Vytvorené</th>
                                <th class="tw-p-2">Publikované</th>
                                <th class="tw-p-2">Akcie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artworks as $a)
                                <tr class="tw-border-b">
                                    <td class="tw-p-2 tw-align-top tw-text-right">{{ $a->id }}</td>
                                    <td class="tw-p-2 tw-align-top tw-flex tw-gap-4">
                                        <img src="{{ $a->item->getImagePath(false, 70) }}"
                                            class="tw-object-contain tw-rounded-md tw-h-20 tw-w-auto" />
                                        <div class="tw-truncate">
                                            <h4 class="tw-font-semibold">{{ $a->title }}</h4>
                                            <p>{{ $a->item->author }}</p>
                                        </div>
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        @datetime($a->created_at)
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        @if ($a->is_published)
                                            <i class="fa fa-check tw-text-green-800"></i>
                                            @datetime($a->published_at)

                                            @if ($a->id == $lastPublishedId)
                                                <br />
                                                <span
                                                    class="tw-inline-block tw-text-xs tw-font-semibold tw-bg-sky-400 tw-text-white tw-px-1 tw-rounded">Najnovšie</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="tw-p-2 tw-align-top">
                                        <div class="tw-flex tw-gap-2">
                                            <x-admin.button outline primary sm :link="route('featured-artworks.edit', $a)">
                                                Upraviť
                                            </x-admin.button>
                                            <x-admin.link-with-confirmation
                                                action="{{ route('featured-artworks.destroy', $a) }}" method="DELETE"
                                                class="tw-font-medium tw-rounded active:tw-shadow-inner tw-inline-block tw-py-1 tw-px-2 tw-text-xs tw-border tw-text-[#d9534f] hover:tw-text-white tw-border-[#d43f3a] hover:tw-bg-[#d2322d] hover:tw-border-[#ac2925]"
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
