@extends('layouts.admin')

@section('title')
presmerovania |
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Presmerovania</h1>

        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
        @endif

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{!! route('redirects.create') !!}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Presmerovanie</th>
                            <th>Vytvorené</th>
                            <th>Aktívne</th>
                            <th>#&nbsp;presmerovaní</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($redirects as $redirect)
                        <tr>
                            <td>{{ $redirect->id }}</td>
                            <td>
                                <code>{{ $redirect->source_url }}</code>
                                <i class="fa fa-arrow-right"></i>
                                <code>{{ $redirect->target_url }}</code>
                            </td>
                            <td>
                                @datetime($redirect->created_at)
                            </td>
                            <td class="text-center">{!! ($redirect->is_enabled) ? '<i class="fa fa-check text-success"></i>' : '' !!}</td>
                            <td  class="text-center">
                                {!! $redirect->counter !!}
                            </td>
                            <td>
                                {!! link_to_action('App\Http\Controllers\RedirectController@edit', 'Upraviť', array($redirect->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                {!! Form::open(array('method' => 'DELETE', 'route' => array('redirects.destroy', $redirect->id), 'class' => 'visible-xs-inline')) !!}
                                    {!! Form::submit('Zmazať', array('class' => 'btn btn-danger btn-xs btn-outline')) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop
