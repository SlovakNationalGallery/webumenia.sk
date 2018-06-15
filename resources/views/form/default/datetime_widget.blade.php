@if ($widget == 'single_text')
    @include('form::form_widget_simple')
@else
    <div @include('form::widget_container_attributes')>
        {!! FormRenderer::errors($form['date']) !!}
        {!! FormRenderer::errors($form['time']) !!}
        {!! FormRenderer::widget($form['date']) !!}
        {!! FormRenderer::widget($form['time']) !!}
    </div>
@endif