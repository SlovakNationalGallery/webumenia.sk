@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
    @php $vars = $widget == 'text' ? ['attr' => ['size' => 1]] : [] @endphp
<div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
    {!! FormRenderer::widget($form['hour'], $vars) !!}@if ($with_minutes):{!! FormRenderer::widget($form['minute'], $vars) !!}@endif @if ($with_seconds):{!! FormRenderer::widget($form['second'], $vars) !!}@endif
</div>
@endif