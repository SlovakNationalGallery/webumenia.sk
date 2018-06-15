<input type="checkbox" @include('form::widget_attributes')
@if (isset($value)) value="{{ $value }}"@endif
@if ($checked) checked="checked"@endif />