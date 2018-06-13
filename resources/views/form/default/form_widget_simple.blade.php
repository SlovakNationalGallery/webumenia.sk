@php $type = isset($type) ? $type : 'text' @endphp
<input type="{{ $type }}" @include('form.default.widget_attributes') @if (!empty($value))value="{{ $value }}" @endif/>