@extends('layouts.admin')

@section('title')
výstavné priestory |
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Výstavné priestory</h1>

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
            {!! Form::open(['url' => 'collection/fill']) !!}
            <div class="panel-heading">
                <a href="{!! route('space.create') !!}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
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
                            <th>Názov</th>
                            <th>Adresa</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($spaces as $s)
			            <tr>
                            <td>
                                <div class="checkbox">
                                    {!! Form::checkbox('ids[]', $s->id, null,  array('class' => 'selectedId')) !!}
                                </div>
                            </td>
			                <td>{!! $s->id !!}</td>
			                <td>{!! $s->name !!}</td>
			                <td>{!! $s->address !!}</td>
			                <td class="action">
                                {!! link_to_action('AuthorityController@edit', 'Upraviť', array($s->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                <a href="{!! $s->getUrl() !!}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    @if (!empty($search))
                        {!! $spaces->appends(array('search' => $search))->render() !!}
                    @else
                        {!! ($spaces->count()!=0) ? $spaces->render() : '' !!}
                    @endif

                </div>


            </div>
            <!-- /.panel-body -->
            {!!Form::close() !!}
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
            $form.attr("action","{!! URL::to('space/destroySelected') !!}");
            $form.trigger('submit');
        });
});
</script>
@stop