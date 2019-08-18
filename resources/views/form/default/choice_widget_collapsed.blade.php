@if ($required && $placeholder !== null && !$placeholder_in_choices && !$multiple && (!isset($attr['size']) || $attr['size'] <= 1))
    @php $required = false @endphp
@endif
<select class="form-control" @include('form.default.widget_attributes') @if ($multiple) multiple="multiple"@endif>
    @if (isset($placeholder))
    <option value=""@if ($required && is_empty($value)) selected="selected"@endif>{{ $placeholder }}</option>
    @endif
    @if (count($preferred_choices) > 0)
        @php $options = $preferred_choices @endphp
        @include('form.default.choice_widget_option')

        @if (count($choices) > 0 && $separator !== null)
            <option disabled="disabled">{{ $separator }}</option>
        @endif
    @endif
    @php $options = $choices @endphp
    @include('form.default.choice_widget_options')
</select>