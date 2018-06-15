<div @include('form::widget_container_attributes')>
    @foreach ($form as $child)
    {!! FormRenderer::widget($child) !!}
    {!! FormRenderer::label($child) !!}
    @endforeach
</div>