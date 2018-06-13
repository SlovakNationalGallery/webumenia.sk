<div {!! FormRenderer::block($form, 'widget_container_attributes', get_defined_vars()) !!}>
    @if ($form->parent === null)
        {!! FormRenderer::errors($form, get_defined_vars()) !!}
    @endif
    {!! FormRenderer::block($form, 'form_rows', get_defined_vars()) !!}
    {!! FormRenderer::rest($form, get_defined_vars()) !!}
</div>