@extends('layouts.master')

@section('title')
  {{ trans('user-collection.title') }}
  |
  @parent
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2 class="bottom-space tw-text-center tw-font-semibold">
                {{ trans('user-collection.title') }} <span class="badge badge-primary badge-sup">beta</span>
            </h2>

            <div class="tw-text-lg mt-5">
                @if($items->isNotEmpty())
                    <p>{{ trans('user-collection.content-intro') }}</p>
                    <p>{{ trans('user-collection.content-usage') }}</p>
                    <p>{{ trans('user-collection.share-info') }}</p>
                @else
                    <div class="tw-text-center tw-text-gray-600">
                        <p>{{ trans('user-collection.empty') }}</p>
                        <img src="{{ asset('/images/no-image/diela/no-image-o.jpg') }}" class="tw-w-1/2" />
                        <p>{{ trans('user-collection.empty_hint') }}&nbsp;<i class="fa fa-star-o"></i>.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<user-collections-store v-slot="store">
    <div :class="{ 'bg-gray-300': store.sharedCollections.present }" class="mt-5 py-5">
        <div class="container">
            <template v-if="store.sharedCollections.present">
                <h2 class="tw-font-semibold tw-text-center m-0">Tvoje výbery</h2>

                <div class="row mt-4">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="bg-white p-4 mt-4" v-for="collection in store.sharedCollections.all" :key="collection.publicId">
                            <user-collections-shared-collection
                                    :public-id="collection.publicId"
                                    :update-token="collection.updateToken"
                                    api-url-template="{{ route('api.shared-user-collections.show', '__PUBLIC_ID__') }}"
                                    share-url-template="{{ route('frontend.shared-user-collections.show', '__PUBLIC_ID__') }}"
                                    edit-url-template="{{ route('frontend.shared-user-collections.edit', ['collection' => '__PUBLIC_ID__', 'token' => '__UPDATE_TOKEN__']) }}"
                                    copy-success-text="{{ trans('general.copied_to_clipboard') }}"
                                />
                        </div>
                    </div>
                </div>
            </template>

            <div v-if="store.items.present" v-cloak class="tw-text-center mt-5">
                <a class="btn btn-dark tw-font-light p-3 px-5" href="{{ route('frontend.shared-user-collections.create', ['ids' => request()->ids ] ) }}">
                    {{ trans('user-collection.share') }}
                </a>

                <div class="tw-text-gray-600 mt-5 mb-5">
                    <p class="mb-0">Do výberu sa vložia všetky aktuálne obľúbené diela.</p>
                    <p v-if="store.sharedCollections.present" class="mt-1 mb-0">Diela v ostatných výberoch ostanú zachované.</p>
                </div>
            </div>
        </div>
    </div>
</user-collections-store>

<div class="container">
    @unless($items->isEmpty())
    <div class="row content-section">
        <div class="col-xs-6">
            <h4 class="inline">{{ $items->total() }} {{ trans_choice('katalog.catalog_artworks', $items->total()) }} </h4>
            <user-collections-clear-button confirm-message="{{ trans('user-collection.clear-confirm') }}" after-clear-redirect="{{ route('frontend.user-collection.show') }}">
                {{ trans('user-collection.clear') }}
            </user-collections-clear-button>
        </div>
        <div class="col-xs-6 tw-text-right">
            {{-- @TODO --}}
            {{-- @formRow($form['sort_by'], ['attr' => ['class' => 'js-dropdown-select']]) --}}
        </div>
    </div>
    @endunless
    <div class="row">
        <div class="col-sm-12">
            <div class="isotope">
            @foreach ($items as $item)
                @include('components.artwork_grid_item', [
                    'item' => $item,
                    'class_names' => 'col-md-3 col-sm-4 col-xs-6',
                ])
            @endforeach
            </div>
        </div>
        <div class="col-sm-12 tw-text-center">
            @include('components.load_more', ['paginator' => $items, 'isotopeContainerSelector' => '.isotope'])
        </div>
    </div>
</div>
@endsection

@section('javascript')
@parent

<script>
    $('.isotope').isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });
</script>
@stop
