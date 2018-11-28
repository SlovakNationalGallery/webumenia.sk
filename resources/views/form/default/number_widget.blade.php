{{-- type="number" doesn't work with floats --}}
@php $type = isset($type) ? $type : 'text' @endphp
@include('form.default.form_widget_simple', ['type' => $type])