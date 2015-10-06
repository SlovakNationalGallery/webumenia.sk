@extends('layouts.admin')

@section('title')
články | 
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Články</h1>

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
                <a href="{{ route('article.create') }}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titulok</th>
                            <th>Kategória</th>
                            <th>Autor</th>
                            <th>Dátum</th>
                            <th>Publikovať</th>
                            <th>Na titulke</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($articles as $i)
			            <tr>
			                <td>{{ $i->id }}</td>
                            <td>{{ $i->title }}</td>
                            <td>{{ ($i->category) ? $i->category->name : '' }}</td>
			                <td>{{ $i->author }}</td>
                            <td>{{ $i->created_at }}</td>
                            <td class="text-center">{{ $i->publish }}</td>
			                <td class="text-center">{{ $i->promote }}</td>
			                <td>
                                {{ link_to_action('ArticleController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) }}
                                <a href="{{ $i->getUrl() }}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>
                                {{ Form::open(array('method' => 'DELETE', 'route' => array('article.destroy', $i->id), 'class' => 'visible-xs-inline')) }}
                                    {{ Form::submit('Zmazať', array('class' => 'btn btn-danger btn-xs btn-outline')) }}
                                {{ Form::close() }}

                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center"><?php echo $articles->links(); ?></div>


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop