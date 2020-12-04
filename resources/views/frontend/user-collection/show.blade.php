@extends('layouts.master')

@section('title')
  {{-- TODO Translate --}}
  Moje
  |
  @parent
@stop

@section('content')
<div class="container">
    @if($items->isEmpty())
     Nemate tu nic! TODO Translate
    @else
    <div class="row">
        <div class="col-sm-12">
            <user-collections-clear-button confirm-message="Naozaj? (TODO Translate)">Vymazat vsetko</user-collections-clear-button>
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
  @endif
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
