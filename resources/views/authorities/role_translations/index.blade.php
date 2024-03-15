@extends('layouts.admin')

@section('title')
    @parent
    - Preklady rolí
@endsection

@section('content')
    <div class="tailwind-rules admin">
        <div class="tw-container tw-mx-auto tw-py-12">
            <x-admin.link href="{{ route('authority.index') }}">
                <i class="fa fa-arrow-left"></i> Späť na autority
            </x-admin.link>
            <h1 class="tw-mt-4 tw-text-4xl">Preklady rolí</h1>
            <hr class="tw-border-1 tw-my-2" />

            @if ($missingCount)
                <x-admin.alert warning class="tw-mt-4">
                    ⚠️
                    @if ($missingCount > 4)
                        Chýba {{ $missingCount }} prekladov.
                    @elseif ($missingCount > 1)
                        Chýbajú {{ $missingCount }} preklady.
                    @else
                        Chýba 1 preklad.
                    @endif
                </x-admin.alert>
            @else
                <x-admin.alert success class="tw-mt-4">
                    ✅ Všetky role sú preložené
                </x-admin.alert>
            @endif

            {{-- <x-admin.button btn primary outline :link="route('authority.role_translations.download')">
                <i class="fa fa-download"></i> Stiahnuť ako CSV
            </x-admin.button> --}}

            <div class="tw-mt-4 tw-overflow-hidden tw-rounded-sm">
                <table class="tw-w-full tw-text-sm">
                    <thead class="tw-border-b-2 tw-border-gray-300 tw-bg-gray-100">
                        <tr>
                            <th class="tw-p-2">Hodnota v CEDVU</th>
                            <th class="tw-p-2">SK</th>
                            <th class="tw-p-2">CS</th>
                            <th class="tw-p-2">EN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($translations as $t)
                            <tr class="tw-border-b">
                                <td class="tw-p-2 tw-align-top tw-font-medium">{{ $t->id }}
                                </td>
                                <td class="tw-p-2 tw-align-top">
                                    @if ($t->sk)
                                        {{ $t->sk }}
                                    @else
                                        <span
                                            class="px-2 py-1 tw-inline-block tw-rounded-full tw-bg-orange-400 tw-text-xs tw-text-white">Chýba</span>
                                    @endif
                                </td>
                                <td class="tw-p-2 tw-align-top">
                                    @if ($t->cs)
                                        {{ $t->cs }}
                                    @else
                                        <span
                                            class="px-2 py-1 tw-inline-block tw-rounded-full tw-bg-orange-400 tw-text-xs tw-text-white">Chýba</span>
                                    @endif
                                </td>
                                <td class="tw-p-2 tw-align-top">
                                    @if ($t->en)
                                        {{ $t->en }}
                                    @else
                                        <span
                                            class="px-2 py-1 tw-inline-block tw-rounded-full tw-bg-orange-400 tw-text-xs tw-text-white">Chýba</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
