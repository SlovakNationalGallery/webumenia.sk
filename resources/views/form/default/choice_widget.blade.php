@if ($expanded)
    {!! FormRenderer::block($form, 'choice_widget_expanded') !!}
@else
    {!! FormRenderer::block($form, 'choice_widget_collapsed') !!}
@endif