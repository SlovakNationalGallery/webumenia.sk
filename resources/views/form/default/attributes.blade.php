@foreach ($attr as $attrname => $attrvalue)
    @if (in_array($attrname, ['placeholder', 'title']))
        {{ $attrname }}="{{ $attrvalue }}"
    @elseif ($attrvalue === true)
        {{ $attrname }}="{{ $attrname }}"
    @elseif ($attrvalue !== false)
        {{ $attrname }}="{{ $attrvalue }}"
    @endif
@endforeach