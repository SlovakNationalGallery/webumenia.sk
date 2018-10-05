@if ($widget == 'single_text')
    @include('form.default.form_widget_simple')
@else
    <div @include('form.default.widget_container_attributes')>
        {!! FormRenderer::errors($form['date']) !!}
        {!! FormRenderer::errors($form['time']) !!}
        {!! FormRenderer::widget($form['date']) !!}
        {!! FormRenderer::widget($form['time']) !!}
    </div>
@endif