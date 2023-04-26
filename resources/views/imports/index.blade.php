@extends('layouts.admin')

@section('title')
CSV Imports |
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">CSV Imports</h1>

        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error: </strong>{!! Session::get('error') !!}</div>
        @endif

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            @can('administer')
            <div class="panel-heading">
                <a href="{!! route('imports.create') !!}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Pridať</a>
            </div>
            @endcan
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Názov</th>
                            <th>Súbor</th>
                            <th>Status</th>
                            <th>Posledný </th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($imports as $i)
			            <tr>
			                <td>{!! $i->id !!}</td>
                            <td>{!! $i->name !!}</td>
                            <td>{!! ($i->lastRecord()) ? $i->lastRecord()->filename : '' !!}</td>
			                <td><span class="h4"><span class="label label-{!! $i->status_class !!}">{!! $i->status !!}</span></span></td>
			                <td>{!! ($i->completed_at) ? $i->completed_at->format('d.m.Y H:i') : '' !!}</td>
			                <td>
                                {!! link_to_action('App\Http\Controllers\ImportController@launch', 'Spustiť', array($i->id), array('class' => 'btn btn-success btn-xs btn-outline', )) !!}
                                {!! link_to_action('App\Http\Controllers\ImportController@show', 'Detail', array($i->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) !!}
                                {!! link_to_action('App\Http\Controllers\ImportController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                @can('administer')
                                {!! Form::open(array('method' => 'DELETE', 'route' => array('imports.destroy', $i->id), 'class' => 'tw-inline md:tw-hidden form-inline')) !!}
                                    {!! Form::submit('Zmazať', array('class' => 'btn btn-danger btn-xs btn-outline')) !!}
                                {!! Form::close() !!}
                                @endcan
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    {!! $imports->appends(@Request::except('page'))->render() !!}
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop
