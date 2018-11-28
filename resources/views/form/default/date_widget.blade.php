@if ($widget == 'single_text')
    @include('form.default.form_widget_simple')
@else
<div @include('form.default.widget_container_attributes')>
    {!! strtr($date_pattern, [
        '{{ year }}' => FormRenderer::widget($form['year']),
        '{{ month }}' => FormRenderer::widget($form['month']),
        '{{ day }}' => FormRenderer::widget($form['day']),
        ])
    !!}
</div>
@endif