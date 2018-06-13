{{--{!! FormRenderer::block($form, $compound ? 'form_widget_compound' : 'form_widget_simple', get_defined_vars())  !!}--}}
@include($compound ? 'form_widget_compound' : 'form_widget_simple')