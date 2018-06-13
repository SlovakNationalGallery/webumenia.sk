<input type="checkbox" @include('form.default.widget_attributes')
@if (isset($value)) value="{{ $value }}"@endif
@if ($checked) checked="checked"@endif />