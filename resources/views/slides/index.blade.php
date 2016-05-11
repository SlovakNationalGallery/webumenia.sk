@extends('layouts.admin')

@section('title')
@parent
- diela
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Carousel</h1>

        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{{ Session::get('message') }}</div>
        @endif

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{{ route('slide.create') }}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Náhľad</th>
                            <th>Nadpis</th>
                            <th>Vytvorený</th>
                            <th>Publikovaný</th>
                            <th>#&nbsp;kliknutí</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($slides as $i)
			            <tr>
			                <td>{{ $i->id }}</td>
                            <td class="text-center"><img src="{{ $i->image_path }}" alt="" class="img-responsive nahlad"></td>
                            <td>
                                {{ $i->title }}<br>
                                {{ $i->subtitle }}
                            </td>
                            <td>
                                {{ $i->created_at }}
                            </td>
                            <td  class="text-center">{{ ($i->publish) ? '<i class="fa fa-check text-success"></i>' : '' }}</td>
                            <td  class="text-center">
                                {{ $i->click_count }}
                            </td>
			                <td>
                                {{ link_to_action('SlideController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) }}
                                {{ Form::open(array('method' => 'DELETE', 'route' => array('slide.destroy', $i->id), 'class' => 'visible-xs-inline')) }}
                                    {{ Form::submit('Zmazať', array('class' => 'btn btn-danger btn-xs btn-outline')) }}
                                {{ Form::close() }}
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