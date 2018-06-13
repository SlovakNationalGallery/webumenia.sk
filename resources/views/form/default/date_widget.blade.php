@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
<div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
    {!! strtr($date_pattern, [
        '{{ year }}' => FormRenderer::widget($form['year']),
        '{{ month }}' => FormRenderer::widget($form['month']),
        '{{ day }}' => FormRenderer::widget($form['day']),
        ])
    !!}
</div>
@endif