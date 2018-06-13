@php $type = isset($type) ? $type : 'text' @endphp
<input type="{{ $type }}" {!! FormRenderer::block($form, 'widget_attributes') !!} @if (!empty($value))value="{{ $value }}" @endif/>