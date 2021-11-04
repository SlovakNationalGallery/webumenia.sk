@extends('layouts.master')

@section('title')
  {{ trans('user-collection.title') }}
  |
  @parent
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4">
            <h2 class="bottom-space text-center">{{ trans('user-collection.title') }} <span class="badge badge-primary badge-sup">beta</span></h2>

            @if($items->isNotEmpty())
                <p>{{ trans('user-collection.content-intro') }}</p>
                <p>{{ trans('user-collection.content-usage') }}</p>
                <p>{{ trans('user-collection.share-info') }}</p>
            @else
                <p class="alert alert-info text-center">{{ trans('user-collection.empty') }}</p>
            @endif
        </div>
    </div>
</div>

@if (Experiment::is('WEBUMENIA-1654-shared-user-collections-with-scena'))
<user-collections-store v-slot="store">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4">
                <div class="text-center mt-4">
                    <a class="btn btn-dark font-light mt-5 p-3 px-5" href="{{ route('frontend.shared-user-collections.create', ['ids' => request()->ids ] ) }}">
                        {{ trans('user-collection.share') }} <i class='ml-1 fa fa-share-alt'></i>
                    </a>

                    <p class="text-muted mt-5">Do výberu sa vložia všetky aktuálne obľúbené diela.</p>
                </div>
                    <ul>
                        <li v-for="collection in store.sharedCollections" :key="collection.publicId">
                        <user-collections-shared-collection
                            :public-id="collection.publicId"
                            :update-token="collection.updateToken"
                            api-url-template="{{ route('api.shared-user-collections.show', '__PUBLIC_ID__') }}"
                            share-url-template="{{ route('frontend.shared-user-collections.show', '__PUBLIC_ID__') }}"
                            edit-url-template="{{ route('frontend.shared-user-collections.edit', ['collection' => '__PUBLIC_ID__', 'token' => '__UPDATE_TOKEN__']) }}"
                        />
                        </li>
                    </ul>

            </div>
        </div>
    </div>
</user-collections-store>
@endif

<div class="container">
    @unless($items->isEmpty())
    <div class="row content-section">
        <div class="col-xs-6">
            <h4 class="inline">{{ $items->total() }} {{ trans_choice('katalog.catalog_artworks', $items->total()) }} </h4>
            <user-collections-clear-button confirm-message="{{ trans('user-collection.clear-confirm') }}" after-clear-redirect="{{ route('frontend.user-collection.show') }}">
                {{ trans('user-collection.clear') }}
            </user-collections-clear-button>
        </div>
        <div class="col-xs-6 text-right">
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
        <div class="col-sm-12 text-center">
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
