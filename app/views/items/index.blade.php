@extends('layouts.admin')

@section('title')
diela | 
@parent
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
                <a href="{{ URL::to('item/reindex') }}" class="btn btn-primary btn-outline"><i class="fa fa-refresh"></i> Reindexovať search</a>
                <a href="{{ URL::to('item/geodata') }}" class="btn btn-primary btn-outline"><i class="fa fa-globe"></i> Doplniť geo dáta</a></div>
                <div class="panel-heading">
                Akcie pre vybraté: {{ Form::select('collection', $collections); }} {{ Form::submit('Pridať do kolekcie', array('class' => 'btn btn-info btn-xs btn-outline')) }} | <a href="#" id="deleteSelected" class="btn btn-danger btn-xs btn-outline">Zmazať</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectall"></th>
                            <th>Id</th>
                            <th>Náhľad</th>
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
                            <td class="text-center"><img src="{{ $i->getImagePath() }}" alt="" class="img-responsive nahlad"></td>
			                <td>{{ $i->title }}</td>
			                <td>{{ $i->author }}</td>
			                <td>{{ $i->dating }}</td>
			                <td>{{ $i->work_type }}</td>
			                <td class="action">
                                {{ link_to_action('ItemController@show', 'Detail', array($i->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) }}&nbsp;{{ link_to_action('ItemController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) }} <br>
                                <a href="{{ $i->getUrl() }}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>
                                <a href="{{ $i->getOaiUrl() }}" class="btn btn-warning btn-xs btn-outline" target="_blank">OAI záznam</a>
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    @if (!empty($search))
                        {{ $items->appends(array('search' => $search))->links() }}
                    @else
                        {{ ($items->count()!=0) ? $items->links() : '' }}
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

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="confirm" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                Naozaj ich zmazať?
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">Zmazať</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">Naspäť</button>
            </div>
        </div>
    </div>
</div>

@stop

{{-- script --}}
@section('script')
<script>
$('#deleteSelected').on('click', function(e){
    var $form=$(this).closest('form');
    e.preventDefault();
    $('#confirm').modal({  })
        .one('click', '#delete', function (e) {
            $form.attr("action","{{ URL::to('item/destroySelected') }}");
            $form.trigger('submit');
        });
});
</script>
@stop