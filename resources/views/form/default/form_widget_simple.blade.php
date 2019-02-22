@php $type = isset($type) ? $type : 'text' @endphp
<input class="form-control" type="{{ $type }}" @include('form.default.widget_attributes') @if (!is_empty($value))value="{{ $value }}" @endif/>