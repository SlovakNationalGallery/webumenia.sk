@extends('layouts.master')

@section('title')
{{ utrans('master.groups') }} |
@parent
@stop

@section('content')
<div class="row">
    <div class="col">
        {{ trans('master.disclaimer', ['subject' => utrans('master.groups')]) }}
    </div>
</div>
@stop