@extends('layouts.master')

@section('og')
@stop

@section('title')
@parent
| autori
@stop

@section('content')


<div class="form-group required has-feedback" style="margin-top: 150px"><label for="pids" class="control-label col-lg-2 col-sm-4">Autori</label>
    <div class="col-lg-10 col-sm-8">
            @if ($authorities->count() == 0)
                <p class="text-center">Žiadni autori</p>
            @endif

            @foreach ($authorities as $i=>$authority)
                <div class="media">
                    <a href="#" class="pull-left">
                        <img src="/images/diela/no-image.jpg" class="media-object" style="max-width: 80px; ">
                    </a>
                    <div class="media-body">                           
                        <a href="#">
                            <em><strong>{{ $authority->name }}</strong> (<em>{{ $authority->type }}</em>)<br>
                            {{ $authority->birth_date }}
                        </a><br>
                    </div>
                </div>
            @endforeach                    
    </div>
    <div class="text-center">{{ $authorities->links() }}</div>
</div>


@stop

@section('javascript')
@stop
