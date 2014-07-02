@extends('layouts.admin')

@section('title')
@parent
- diela
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Diela</h1>

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
            {{ Form::open(['url' => 'collection/fill']) }}
            <div class="panel-heading">
                <a href="{{ route('item.create') }}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
                <a href="{{ URL::to('item/backup') }}" class="btn btn-primary btn-outline"><i class="fa fa-floppy-o"></i> Zazálohovať</a> 
                <a href="{{ URL::to('item/geodata') }}" class="btn btn-primary btn-outline"><i class="fa fa-globe"></i> Doplniť geo dáta</a><br>
                Pridať vybraté do kolekcie: {{ Form::select('collection', $collections); }} &nbsp;  {{ Form::submit('Pridať', array('class' => 'btn btn-default')) }} 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectall"></th>
                            <th>Id</th>
                            <th>Názov</th>
                            <th>Autor</th>
                            <th>Dátum</th>
                            <th>výtvarný druh</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($items as $i)
			            <tr>
                            <td>
                                <div class="checkbox">
                                    {{ Form::checkbox('ids[]', $i->id, null,  array('class' => 'selectedId')) }}
                                </div>
                            </td>
			                <td>{{ $i->id }}</td>
			                <td>{{ $i->title }}</td>
			                <td>{{ $i->author }}</td>
			                <td>{{ $i->dating }}</td>
			                <td>{{ $i->work_type }}</td>
			                <td class="action">{{ link_to_action('ItemController@show', 'Detail', array($i->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) }}&nbsp;{{ link_to_action('ItemController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) }}</td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    @if (!empty($search))
                        {{ $items->appends(array('search' => $search))->links() }}
                    @else
                        {{ $items->links() }}
                    @endif
                    
                </div>


            </div>
            <!-- /.panel-body -->
            {{Form::close() }}
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

@stop