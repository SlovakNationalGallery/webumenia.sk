@extends('layouts.master')

@section('title')
  {{ trans('user-collection.title') }}
  |
  @parent
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4 underlined-links">
            <h2 class="bottom-space text-center">{{ trans('user-collection.title') }} <span class="badge badge-primary badge-sup">beta</span></h2>

            @if($items->isEmpty())
                <p class="alert alert-info text-center">{{ trans('user-collection.empty') }}</p>
            @else
                <p>{!! trans('user-collection.content-intro') !!}</p>
                <p>{!! trans('user-collection.content-usage') !!}</p>
                <p>{!! trans('user-collection.content-feedback') !!}</p>
            @endif
        </div>
    </div>

    @unless($items->isEmpty())
    <div class="row content-section">
        <div class="col-xs-6">
            <h4 class="inline">{{ count($items) }} {{ trans_choice('katalog.catalog_artworks', count($items)) }} </h4>
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
