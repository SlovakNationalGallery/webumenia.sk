@if ($required && $placeholder !== null && !$placeholder_in_choices && !$multiple && (!isset($attr['size']) || $attr['size'] <= 1))
    @php $required = false @endphp
@endif
<select @include('form::widget_attributes') @if ($multiple) multiple="multiple"@endif>
    @if (isset($placeholder))
    <option value=""@if ($required && empty($value)) selected="selected"@endif>{{ $placeholder }}</option>
    @endif
    @if (count($preferred_choices) > 0)
        @php $options = $preferred_choices @endphp
        @include('form::choice_widget_option')

        @if (count($choices) > 0 && $separator !== null)
            <option disabled="disabled">{{ $separator }}</option>
        @endif
    @endif
    @php $options = $choices @endphp
    @include('form::choice_widget_options')
</select>