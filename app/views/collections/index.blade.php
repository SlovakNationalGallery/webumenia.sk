@extends('layouts.admin')

@section('title')
@parent
- diela
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Kolekcie</h1>

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
                <a href="{{ route('collection.create') }}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Poradie</th>
                            <th>Názov</th>
                            <th>Počet diel</th>
                            <th>Dátum</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($collections as $i)
			            <tr>
			                <td>{{ $i->order }}</td>
			                <td>{{ $i->name }}</td>
			                <td>{{ $i->items()->count(); }}</td>
			                <td>{{ $i->created_at }}</td>
			                <td>{{ link_to_action('CollectionController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) }}</td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center"><?php echo $collections->links(); ?></div>


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop