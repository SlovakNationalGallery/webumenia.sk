@props(['id'])

<img {{ $attributes->merge([
  'src' => route('dielo.nahlad', ['id' => $id, 'width' => 600]),
  'srcset' => collect([220, 300, 600, 800])
    ->map(fn ($width) => route('dielo.nahlad', ['id' => $id, 'width' => $width]) . " ${width}w")
    ->join(', ')
]) }} />
