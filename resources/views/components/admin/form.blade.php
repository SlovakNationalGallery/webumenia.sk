{!! Form::model($model, compact('url', 'method', 'files')) !!}
    {{ $slot }}
{!! Form::close() !!}
