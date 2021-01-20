@isset($notice)
    <div class="alert alert-{{ $notice->alert_class }}">
        {!! $notice->content !!}
    </div>
@endisset
