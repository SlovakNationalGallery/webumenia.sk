@if ($similar_by_color)
    @foreach ($similar_by_color as $item)
        @include('components.artwork_grid_item', [
            'item' => $item,
            'isotope_item_selector_class' => 'item',
            'class_names' => 'col-xs-6 px-5',
            'hide_zoom' => true,
            'hide_dating' => true
        ])
    @endforeach
@endif
