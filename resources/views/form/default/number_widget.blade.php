{{-- type="number" doesn't work with floats --}}
@php $type = isset($type) ? $type : 'text' @endphp
@include('form::form_widget_simple')