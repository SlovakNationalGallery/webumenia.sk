@if ($expanded)
    @include('form::choice_widget_expanded')
@else
    @include('form::choice_widget_collapsed')
@endif