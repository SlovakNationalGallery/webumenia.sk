@extends('layouts.admin')

@section('content')
    <div class="tailwind-rules">
        <div class="tw-container tw-max-w-screen-md tw-pt-12 mx-auto">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-admin.form :model="$featuredArtwork">
                <x-admin.label value="Dielo" />
                <query-string v-slot="qs" class="sm:tw-w-2/3">
                    <autocomplete v-bind:remote="{ url: '/katalog/suggestions?search=%QUERY' }"
                        placeholder="Zadaj názov, autora, ..." value="{{ request('itemId') }}"
                        v-on:input="({id}) => qs.set('itemId', id)" option-label="id">
                        <template v-slot:option="option">
                            <div class="tw-flex">
                                <img :src="option.image" class="tw-h-16 tw-w-16 tw-object-cover">
                                <div class="tw-px-4 tw-py-2 tw-max-w-full tw-truncate">
                                    <h4 class="tw-font-semibold">@{{ option.title }}</h4>
                                    <span>@{{ option.author }} ∙ @{{ option.id }}</span>
                                </div>
                            </div>
                        </template>
                    </autocomplete>
                </query-string>

                @if ($item)
                    <div class="tw-grid sm:tw-grid-cols-3 tw-gap-x-8 tw-mt-8">
                        <img src="{{ $item->getImagePath() }}" class="tw-object-contain tw-rounded-md" />
                        <div class="tw-col-span-2">
                            <x-admin.label for="title" value="Názov" />
                            <x-admin.input id="title" name="title" :value="old('title', $featuredArtwork->title)"
                                :placeholder="$item->title" />

                            <x-admin.label for="author" value="Autori" class="tw-mt-4" />
                            @foreach ($authorLinks as $a)
                                <x-admin.a href="{{ $a->url }}">{{ $a->label }}</x-admin.a>
                                {{ $loop->last ? '' : ', ' }}
                            @endforeach

                            <x-admin.label for="metadata" value="Metadáta" class="tw-mt-4" />
                            @foreach ($metadataLinks as $m)
                                @if ($m->url)
                                    <x-admin.a href="{{ $m->url }}">{{ $m->label }}</x-admin.a>{{ $loop->last ? '' : ', ' }}
                                @else
                                    {{ $m->label }}{{ $loop->last ? '' : ', ' }}
                                @endif
                            @endforeach
                        </div>
                        <div class="tw-col-span-3 tw-mt-4">
                            <x-admin.label for="description" value="Popis" class="tw-mt-4" />
                            <textarea id="description"
                                class="wysiwyg">{{ old('description', $featuredArtwork->description) }}</textarea>
                        </div>
                        <div class="tw-col-span-3 tw-mt-8">
                            <x-admin.checkbox id="publish" name="publish"
                                :checked="old('publish', $featuredArtwork->is_published)" />
                            <label for="publish" class="tw-select-none tw-ml-1 tw-font-normal">Publikovať</label>
                            @if ($featuredArtwork->is_published)
                                <span class="tw-text-gray-300">{{ $featuredArtwork->published_at }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="tw-text-center tw-mt-8">
                        <x-admin.button primary>
                            Uložiť
                        </x-admin.button>
                        <x-admin.button :link="route('featured-artworks.index')">
                            Zrušiť
                        </x-admin.button>
                    </div>
                @endif
            </x-admin.form>
        </div>
    </div>
@endsection
