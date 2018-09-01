<div @include('form::widget_container_attributes')>
    @if ($form->parent === null)
        {!! FormRenderer::errors($form) !!}
    @endif
    @include('form::form_rows')
    {!! FormRenderer::rest($form) !!}
</div>