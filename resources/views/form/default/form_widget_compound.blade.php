<div @include('form.default.widget_container_attributes')>
    @if ($form->parent === null)
        {!! FormRenderer::errors($form) !!}
    @endif
    @include('form.default.form_rows')
    {!! FormRenderer::rest($form) !!}
</div>