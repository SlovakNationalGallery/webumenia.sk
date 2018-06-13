<input type="checkbox" {!! FormRenderer::block($form, 'widget_attributes') !!}
@if (isset($value)) value="{{ $value }}"@endif
@if ($checked) checked="checked"@endif />