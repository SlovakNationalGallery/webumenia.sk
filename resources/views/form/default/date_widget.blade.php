@if ($widget == 'single_text')
    @include('form::form_widget_simple')
@else
<div @include('form::widget_container_attributes')>
    {!! strtr($date_pattern, [
        '{{ year }}' => FormRenderer::widget($form['year']),
        '{{ month }}' => FormRenderer::widget($form['month']),
        '{{ day }}' => FormRenderer::widget($form['day']),
        ])
    !!}
</div>
@endif