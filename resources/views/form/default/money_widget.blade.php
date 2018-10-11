{!! strtr($money_pattern, [
    '{{ widget }}' => FormRenderer::block($form, 'form_widget_simple')
]) !!}