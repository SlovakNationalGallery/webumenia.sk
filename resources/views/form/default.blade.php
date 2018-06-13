@section('attributes')
@foreach ($attr as $attrname => $attrvalue)
    @if (in_array($attrname, ['placeholder', 'title']))
        {{ $attrname }}="{{ $attrvalue }}"
    @elseif ($attrvalue === true)
        {{ $attrname }}="{{ $attrname  }}"
    @elseif ($attrvalue !== false)
        {{ $attrname }}="{{ $attrvalue }}"
    @endif
@endforeach
@endsection

@section('button_attributes')
id="{{ $id }}" name="{{ $full_name }}"@if ($disabled) disabled="disabled"@endif
{!! FormRenderer::block($form, 'attributes') !!}
@endsection

@section('button_row')
<div>
    {!! FormRenderer::widget($form) !!}
</div>
@endsection

@section('button_widget')
@if (empty($label))
    @if (!empty($label_format))
        @php $label = strtr($label_format, ['%name%' => $name, '%id%' => $id]) @endphp
    @else
        @php $label = FormRenderer::humanize($name) @endphp
    @endif
@endif
<button type="{{ isset($type) ? $type : 'button' }}" {!! FormRenderer::block($form, 'button_attributes') !!}>{{ $label }}</button>
@endsection

@section('checkbox_widget')
<input type="checkbox" {!! FormRenderer::block($form, 'widget_attributes') !!}
@if (isset($value)) value="{{ $value }}"@endif
@if ($checked) checked="checked"@endif />
@endsection

@section('choice_widget')
@if ($expanded)
    {!! FormRenderer::block($form, 'choice_widget_expanded') !!}
@else
    {!! FormRenderer::block($form, 'choice_widget_collapsed') !!}
@endif
@endsection

@section('choice_widget_collapsed')
@if ($required && $placeholder !== null && !$placeholder_in_choices && !$multiple && (!isset($attr['size']) || $attr['size'] <= 1))
    @php $required = false @endphp
@endif
<select {!! FormRenderer::block($form, 'widget_attributes') !!} @if ($multiple) multiple="multiple"@endif>
    @if (isset($placeholder))
    <option value=""@if ($required && empty($value)) selected="selected"@endif>{{ $placeholder }}</option>
    @endif
    @if (count($preferred_choices) > 0)
        @php $options = $preferred_choices @endphp
        {!! FormRenderer::block($form, 'choice_widget_option') !!}

        @if (count($choices) > 0 && $separator !== null)
            <option disabled="disabled">{{ $separator }}</option>
        @endif
    @endif
    @php $options = $choices @endphp
    {!! FormRenderer::block($form, 'choice_widget_options') !!}
</select>
@endsection

@section('choice_widget_expanded')
<div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
    @foreach ($form as $child)
    {!! FormRenderer::widget($child) !!}
    {!! FormRenderer::label($child) !!}
    @endforeach
</div>
@endsection

@section('choice_widget_options')
@foreach ($options as $group_label => $choice)
    @if ($choice instanceof Traversable || is_array($choice))
        <optgroup label="{{ $group_label }}">
            @php $options = $choice @endphp
            {!! FormRenderer::block($form, 'choice_widget_options') !!}
        </optgroup>
    @else
        <option value="{{ $choice['value'] }}"@if ($choice['attr']) {!! FormRenderer::block($form, 'attributes', ['attr' => $choice['attr']]) !!}@endif @if (is_selected_choice($choice, $value)) selected="selected"@endif>{{ $choice['label'] }}</option>
    @endif
@endforeach
@endsection

@section('collection_widget')
@if (isset($prototype))
    @php $attr = array_merge($attr, ['data-prototype' => FormRenderer::row($prototype) ]) @endphp
@endif
{!! FormRenderer::block($form, 'form_widget', get_defined_vars()) !!}
@endsection

@section('date_widget')
@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
<div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
    {!! strtr($date_pattern, [
        '{{ year }}' => FormRenderer::widget($form['year']),
        '{{ month }}' => FormRenderer::widget($form['month']),
        '{{ day }}' => FormRenderer::widget($form['day']),
        ])
    !!}
</div>
@endif
@endsection

@section('dateinterval_widget')
@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
<div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
    {!! FormRenderer::errors($form) !!}
    <table class="{{ isset($table_class) ? $table_class : '' }}">
        <thead>
        <tr>
            @if ($with_years)<th>{!! FormRenderer::label($form['years']) !!}</th>@endif
            @if ($with_months)<th>{!! FormRenderer::label($form['months']) !!}</th>@endif
            @if ($with_weeks)<th>{!! FormRenderer::label($form['weeks']) !!}</th>@endif
            @if ($with_days)<th>{!! FormRenderer::label($form['days']) !!}</th>@endif
            @if ($with_hours)<th>{!! FormRenderer::label($form['hours']) !!}</th>@endif
            @if ($with_minutes)<th>{!! FormRenderer::label($form['minutes']) !!}</th>@endif
            @if ($with_seconds)<th>{!! FormRenderer::label($form['seconds']) !!}</th>@endif
        </tr>
        </thead>
        <tbody>
        <tr>
            @if ($with_years)<th>{!! FormRenderer::widget($form['years']) !!}</th>@endif
            @if ($with_months)<th>{!! FormRenderer::widget($form['months']) !!}</th>@endif
            @if ($with_weeks)<th>{!! FormRenderer::widget($form['weeks']) !!}</th>@endif
            @if ($with_days)<th>{!! FormRenderer::widget($form['days']) !!}</th>@endif
            @if ($with_hours)<th>{!! FormRenderer::widget($form['hours']) !!}</th>@endif
            @if ($with_minutes)<th>{!! FormRenderer::widget($form['minutes']) !!}</th>@endif
            @if ($with_seconds)<th>{!! FormRenderer::widget($form['seconds']) !!}</th>@endif
        </tr>
        </tbody>
    </table>
    @if ($with_invert){!! FormRenderer::widget($form['invert']) !!}@endif
</div>
@endif
@endsection

@section('datetime_widget')
@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
    <div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
        {!! FormRenderer::errors($form['date']) !!}
        {!! FormRenderer::errors($form['time']) !!}
        {!! FormRenderer::widget($form['date']) !!}
        {!! FormRenderer::widget($form['time']) !!}
    </div>
@endif
@endsection

@section('email_widget')
@php $type = isset($type) ? $type : 'email' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('form')
{!! FormRenderer::start($form) !!}
{!! FormRenderer::widget($form) !!}
{!! FormRenderer::end($form) !!}
@endsection

@section('form_end')
@if (!isset($render_rest) || $render_rest)
{!! FormRenderer::rest($form) !!}
@endif
</form>
@endsection

@section('form_errors')
@if (count($errors) > 0)
<ul>
    @foreach ($errors as $error)
    <li>{{ $errors['message'] }}</li>
    @endforeach
</ul>
@endif
@endsection

@section('form_label')
@if ($label !== false)
    @if (!$compound)
        @php $label_attr = array_merge($label_attr, ['for' => $id]) @endphp
    @endif
    @if ($required)
        @php $class = isset($label_attr['class']) ? trim($label_attr['class'] . ' required') : 'required'; @endphp
        @php $label_attr = array_merge($label_attr, ['class' => $class]) @endphp
    @endif
    @if (empty($label))
        @if (!empty($label_format))
            @php $label = strtr($label_format, ['%name%' => $name, '%id%' => $id]) @endphp
        @else
            @php $label = FormRenderer::humanize($name) @endphp
        @endif
    @endif
    <label
    @if ($label_attr)
        {!! FormRenderer::block($form, 'attributes', ['attr' => $label_attr]) !!}
    @endif
    >{{ $label }}</label>
@endif
@endsection

@section('form_rest')
@foreach ($form as $child)
    @if (!$child->isRendered())
        {!! FormRenderer::row($child) !!}
    @endif
@endforeach

@if (!$form->isMethodRendered())
    @php $form->setMethodRendered() @endphp
    @php $method = strtoupper($method) @endphp
    @if (in_array($method, ['GET', 'POST']))
        @php $form_method = $method @endphp
    @else
        @php $form_method = 'POST' @endphp
    @endif

    @if ($form_method != $method)
<input type="hidden" name="_method" value="{{ $method }}" />
    @endif
@endif
@endsection

@section('form_row')
<div>
    {!! FormRenderer::label($form) !!}
    {!! FormRenderer::errors($form) !!}
    {!! FormRenderer::widget($form) !!}
</div>
@endsection

@section('form_rows')
@foreach ($form as $child)
    {!! FormRenderer::row($child) !!}
@endforeach
@endsection

@section('form_start')
@php $form->setMethodRendered() @endphp
@php $method = strtoupper($method) @endphp
@if (in_array($method, ['GET', 'POST']))
    @php $form_method = $method @endphp
@else
    @php $form_method = 'POST' @endphp
@endif
<form name="{{ $name }}" method="{{ strtolower($form_method) }}"@if ($action != '') action="{{ $action }}"@endif @foreach ($attr as $attrname => $attrvalue ) %} {{ $attrname }}="{{ $attrvalue }}"@endforeach @if ($multipart) enctype="multipart/form-data"@endif>
    @if ($form_method != $method)
    <input type="hidden" name="_method" value="{{ $method }}" />
    @endif
@endsection

@section('form_widget')
{!! FormRenderer::block($form, $compound ? 'form_widget_compound' : 'form_widget_simple', get_defined_vars())  !!}
@endsection

@section('form_widget_compound')
<div {!! FormRenderer::block($form, 'widget_container_attributes', get_defined_vars()) !!}>
    @if ($form->parent === null)
        {!! FormRenderer::errors($form, get_defined_vars()) !!}
    @endif
    {!! FormRenderer::block($form, 'form_rows', get_defined_vars()) !!}
    {!! FormRenderer::rest($form, get_defined_vars()) !!}
</div>
@endsection

@section('form_widget_simple')
@php $type = isset($type) ? $type : 'text' @endphp
<input type="{{ $type }}" {!! FormRenderer::block($form, 'widget_attributes') !!} @if (!empty($value))value="{{ $value }}" @endif/>
@endsection

@section('hidden_row')
{!! FormRenderer::widget($form) !!}
@endsection

@section('hidden_widget')
@php $type = isset($type) ? $type : 'hidden' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('integer_widget')
@php $type = isset($type) ? $type : 'number' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('money_widget')
{!! strtr($money_pattern, [
    '{{ widget }}' => FormRenderer::block($form, 'form_widget_simple')
]) !!}
@endsection

@section('number_widget')
{{-- type="number" doesn't work with floats --}}
@php $type = isset($type) ? $type : 'text' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('password_widget')
@php $type = isset($type) ? $type : 'password' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('percent_widget')
@php $type = isset($type) ? $type : 'text' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('radio_widget')
<input type="radio" {!! FormRenderer::block($form, 'widget_attributes') !!}@if (isset($value)) value="{{ $value }}"@endif @if ($checked) checked="checked"@endif />
@endsection

@section('range_widget')
@php $type = isset($type) ? $type : 'range' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('repeated_row')
{{--No need to render the errors here, as all errors are mapped--}}
{{--to the first child (see RepeatedTypeValidatorExtension).--}}
{!! FormRenderer::block($form, 'form_rows') !!}
@endsection

@section('reset_widget')
@php $type = isset($type) ? $type : 'reset' @endphp
{!! FormRenderer::block($form, 'button_widget', ['type' => $type]) !!}
@endsection

@section('search_widget')
@php $type = isset($type) ? $type : 'search' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('submit_widget')
@php $type = isset($type) ? $type : 'submit' @endphp
{!! FormRenderer::block($form, 'button_widget', ['type' => $type]) !!}
@endsection

@section('textarea_widget')
<textarea {!! FormRenderer::block($form, 'widget_attributes') !!}>{{ $value }}</textarea>
@endsection

@section('time_widget')
@if ($widget == 'single_text')
    {!! FormRenderer::block($form, 'form_widget_simple') !!}
@else
    @php $vars = $widget == 'text' ? ['attr' => ['size' => 1]] : [] @endphp
<div {!! FormRenderer::block($form, 'widget_container_attributes') !!}>
    {!! FormRenderer::widget($form['hour'], $vars) !!}@if ($with_minutes):{!! FormRenderer::widget($form['minute'], $vars) !!}@endif @if ($with_seconds):{!! FormRenderer::widget($form['second'], $vars) !!}@endif
</div>
@endif
@endsection

@section('url_widget')
@php $type = isset($type) ? $type : 'url' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}
@endsection

@section('widget_attributes')
id="{{ $id }}" name="{{ $full_name }}"
@if ($disabled) disabled="disabled"@endif
@if ($required) required="required"@endif
{!! FormRenderer::block($form, 'attributes') !!}
@endsection

@section('widget_container_attributes')
@if (isset($id))
    id="{{ $id }}"
@endif
{!! FormRenderer::block($form, 'attributes', get_defined_vars()) !!}
@endsection

