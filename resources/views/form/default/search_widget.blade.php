@php $type = isset($type) ? $type : 'search' @endphp
@include('form.default.form_widget_simple', ['type' => $type])