@if ($required && $placeholder !== null && !$placeholder_in_choices && !$multiple && (!isset($attr['size']) || $attr['size'] <= 1))
    @php $required = false @endphp
@endif

@php $attr = array_merge($attr, ['class' => trim(($attr['class'] ?? '') . ' form-control')]) @endphp

<select @include('form.default.widget_attributes') @if ($multiple) multiple="multiple"@endif>
    @if (isset($placeholder))
    <option value=""@if ($required && is_empty($value)) selected="selected"@endif>@if ($placeholder != ''){{ ($translation_domain === false ? $placeholder : trans($translation_domain ? "$translation_domain.$placeholder" : $placeholder)) }}@endif</option>
    @endif
    @if (count($preferred_choices) > 0)
        @php $options = $preferred_choices @endphp
        @include('form.default.choice_widget_options')
        @if (count($choices) > 0 && $separator !== null)
            <option disabled="disabled">{{ $separator }}</option>
        @endif
    @endif
    @php $options = $choices @endphp
    @include('form.default.choice_widget_options')
</select>