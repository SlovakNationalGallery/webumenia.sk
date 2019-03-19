@if ($similar_by_color)
    @include('components.artwork_carousel', [
        'slick_target' => "artworks-preview",
        'items' => $similar_by_color,
    ])
@endif
