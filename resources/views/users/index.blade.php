@extends('layouts.admin')

@section('title')
@parent
- diela
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Užívatelia</h1>

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
                <a href="{!! route('user.create') !!}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Login</th>
                            <th>Meno</th>
                            <th>Skupina</th>
                            <th>Dátum vytvorenia</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($users as $i)
			            <tr>
			                <td>{!! $i->username !!}</td>
                            <td>{!! $i->name !!}</td>
			                <td>{!! $i->roles()->first()->name !!}</td>
                            <td>{!! $i->created_at !!}</td>
			                <td>
                                {{-- {!! link_to_action('UserController@show', 'Detail', array($i->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) !!}  --}}
                                {!! link_to_action('UserController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                {!! Form::open(array('method' => 'DELETE', 'route' => array('user.destroy', $i->id), 'class' => 'visible-xs-inline')) !!}
                                    {!! Form::submit('Zmazať', array('class' => 'btn btn-danger btn-xs btn-outline')) !!}
                                {!! Form::close() !!}
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center"><?php echo $users->links(); ?></div>


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop