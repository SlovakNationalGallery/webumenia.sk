@if ($widget == 'single_text')
    @include('form.default.form_widget_simple')
@else
<div @include('form.default.widget_container_attributes')>
    {!! FormRenderer::errors($form) !!}
    <table class="{{ isset($table_class) ? $table_class : '' }}">
        <thead>
        <tr>
            @if ($with_years)<th>{!! FormRenderer::label($form['years']) !!}</th>@endif
            @if ($with_months)<th>{!! FormRenderer::label($form['months']) !!}</th>@endif
            @if ($with_weeks)<th>{!! FormRenderer::label($form['weeks']) !!}</th>@endif
            @if ($with_days)<th>{!! FormRenderer::label($form['days']) !!}</th>@endif
            @if ($with_hours)<th>{!! FormRenderer::label($form['hours']) !!}</th>@endif
            @if ($with_minutes)<th>{!! FormRenderer::label($form['minutes']) !!}</th>@endif
            @if ($with_seconds)<th>{!! FormRenderer::label($form['seconds']) !!}</th>@endif
        </tr>
        </thead>
        <tbody>
        <tr>
            @if ($with_years)<th>{!! FormRenderer::widget($form['years']) !!}</th>@endif
            @if ($with_months)<th>{!! FormRenderer::widget($form['months']) !!}</th>@endif
            @if ($with_weeks)<th>{!! FormRenderer::widget($form['weeks']) !!}</th>@endif
            @if ($with_days)<th>{!! FormRenderer::widget($form['days']) !!}</th>@endif
            @if ($with_hours)<th>{!! FormRenderer::widget($form['hours']) !!}</th>@endif
            @if ($with_minutes)<th>{!! FormRenderer::widget($form['minutes']) !!}</th>@endif
            @if ($with_seconds)<th>{!! FormRenderer::widget($form['seconds']) !!}</th>@endif
        </tr>
        </tbody>
    </table>
    @if ($with_invert){!! FormRenderer::widget($form['invert']) !!}@endif
</div>
@endif