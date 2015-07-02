@extends('layouts.admin')

@section('title')
@parent
- autority
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Autority</h1>

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
                <a href="{{ route('authority.create') }}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
                <a href="{{ URL::to('authority/reindex') }}" class="btn btn-primary btn-outline"><i class="fa fa-refresh"></i> Reindexovať search</a>
                <div class="panel-heading">
                Akcie pre vybraté: <a href="#" id="deleteSelected" class="btn btn-danger btn-xs btn-outline">Zmazať</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectall"></th>
                            <th>Id</th>
                            <th>Type</th>
                            <th>Meno</th>
                            <th>Miesto</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($authorities as $a)
			            <tr>
                            <td>
                                <div class="checkbox">
                                    {{ Form::checkbox('ids[]', $a->id, null,  array('class' => 'selectedId')) }}
                                </div>
                            </td>
			                <td>{{ $a->id }}</td>
			                <td>{{ $a->type }}</td>
			                <td>{{ $a->name }}</td>
			                <td>{{ $a->place }}</td>
			                <td class="action">
                                {{ link_to_action('AuthorityController@edit', 'Upraviť', array($a->id), array('class' => 'btn btn-primary btn-xs btn-outline')) }}    
                                <a href="{{ $a->getDetailUrl() }}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>     
                                <a href="{{ $a->getOaiUrl() }}" class="btn btn-warning btn-xs btn-outline" target="_blank">OAI záznam</a>                             
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    @if (!empty($search))
                        {{ $authorities->appends(array('search' => $search))->links() }}
                    @else
                        {{ ($authorities->count()!=0) ? $authorities->links() : '' }}
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