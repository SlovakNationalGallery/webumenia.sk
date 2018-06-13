@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
    <div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
        {!! FormRenderer::errors($form['date']) !!}
        {!! FormRenderer::errors($form['time']) !!}
        {!! FormRenderer::widget($form['date']) !!}
        {!! FormRenderer::widget($form['time']) !!}
    </div>
@endif