@php $form->setMethodRendered() @endphp
@php $method = strtoupper($method) @endphp
@if (in_array($method, ['GET', 'POST']))
    @php $form_method = $method @endphp
@else
    @php $form_method = 'POST' @endphp
@endif
<form name="{{ $name }}" method="{{ strtolower($form_method) }}"@if ($action != '') action="{{ $action }}"@endif @foreach ($attr as $attrname => $attrvalue ) %} {{ $attrname }}="{{ $attrvalue }}"@endforeach @if ($multipart) enctype="multipart/form-data"@endif>
    @if ($form_method != $method)
    <input type="hidden" name="_method" value="{{ $method }}" />
    @endif