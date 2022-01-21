@foreach ($options as $group_label => $choice)
    @if ($choice instanceof Traversable || is_array($choice))
        <optgroup label="{{ $group_label }}">
            @php $options = $choice @endphp
            @include('form.default.choice_widget_options')
        </optgroup>
    @else
        <option value="{{ $choice->value }}"@if ($choice->attr) @include('form.default.attributes', ['attr' => $choice->attr]) @endif @if (is_selected_choice($choice, $value)) selected="selected"@endif>{{ $choice_translation_domain === false ? $choice->label : trans("$choice_translation_domain.$choice->label") }}</option>
    @endif
@endforeach