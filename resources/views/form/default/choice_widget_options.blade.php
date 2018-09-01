@foreach ($options as $group_label => $choice)
    @if ($choice instanceof Traversable || is_array($choice))
        <optgroup label="{{ $group_label }}">
            @php $options = $choice @endphp
            @include('form::choice_widget_options')
        </optgroup>
    @else
        <option value="{{ $choice['value'] }}"@if ($choice['attr']) @include('form::attributes')@endif @if (is_selected_choice($choice, $value)) selected="selected"@endif>{{ $choice['label'] }}</option>
    @endif
@endforeach