<div @include('form.default.widget_container_attributes')>
    @foreach ($form as $child)
    {!! FormRenderer::widget($child) !!}
    {!! FormRenderer::label($child, null, ['translation_domain' => $choice_translation_domain]) !!}
    @endforeach
</div>