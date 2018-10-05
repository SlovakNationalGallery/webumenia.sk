@if (isset($prototype))
    @php
    $attr = array_merge($attr, ['data-prototype' => FormRenderer::row($prototype)]);
    $attr = array_merge($attr, ['data-prototype-name' => $prototype->vars['name']]);
    @endphp
@endif
@php
$attr = array_merge($attr, ['data-allow-add' => $allow_add ? 1 : 0]);
$attr = array_merge($attr, ['data-allow-remove' => $allow_delete ? 1 : 0]);
$attr = array_merge($attr, ['data-name-prefix' => $full_name ? 1 : 0]);
@endphp
@include('form.default.form_widget')