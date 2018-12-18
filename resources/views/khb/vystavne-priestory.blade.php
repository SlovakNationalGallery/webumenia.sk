@extends('layouts.master')

@section('title')
{{ utrans('master.spaces') }} |
@parent
@stop

@section('content')
<div class="row">
    <div class="col">
        {{ trans('master.disclaimer', ['subject' => utrans('master.spaces')]) }}
    </div>
</div>
@stop