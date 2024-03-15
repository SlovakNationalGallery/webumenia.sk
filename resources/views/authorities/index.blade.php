@extends('layouts.admin')

@section('title')
autority |
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Autority</h1>

        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
        @endif

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

{!! Form::open(['url' => 'collection/fill']) !!}

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{{ route('authority.create') }}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
                <a href="{{ URL::to('authority/reindex') }}" class="btn btn-primary btn-outline"><i class="fa fa-refresh"></i> Reindexovať search</a>
                <a href="{{ route('authority.role-translations.index') }}" class="btn btn-primary btn-outline">Preklady rolí</a>
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
                                    {!! Form::checkbox('ids[]', $a->id, null,  array('class' => 'selectedId')) !!}
                                </div>
                            </td>
			                <td>{!! $a->id !!}</td>
			                <td>{!! $a->type !!}</td>
			                <td>{!! $a->name !!}</td>
			                <td>{!! $a->place !!}</td>
			                <td class="action">
                                {!! link_to_action('App\Http\Controllers\AuthorityController@edit', 'Upraviť', array($a->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                <a href="{!! $a->getUrl() !!}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>
                                @if($a->oai_url)<a href="{{ $a->oai_url }}" class="btn btn-warning btn-xs btn-outline" target="_blank">OAI záznam</a>@endif
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    @if (!empty($search))
                        {!! $authorities->appends(array('search' => $search))->render() !!}
                    @else
                        {!! ($authorities->count()!=0) ? $authorities->render() : '' !!}
                    @endif

                </div>


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

{!! Form::close() !!}

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
            $form.attr("action","{!! URL::to('authority/destroySelected') !!}");
            $form.trigger('submit');
        });
});
</script>
@stop