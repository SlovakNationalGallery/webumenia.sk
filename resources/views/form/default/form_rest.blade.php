@foreach ($form as $child)
    @if (!$child->isRendered())
        {!! FormRenderer::row($child) !!}
    @endif
@endforeach

@if (!$form->isMethodRendered())
    @php $form->setMethodRendered() @endphp
    @php $method = strtoupper($method) @endphp
    @if (in_array($method, ['GET', 'POST']))
        @php $form_method = $method @endphp
    @else
        @php $form_method = 'POST' @endphp
    @endif

    @if ($form_method != $method)
<input type="hidden" name="_method" value="{{ $method }}" />
    @endif
@endif