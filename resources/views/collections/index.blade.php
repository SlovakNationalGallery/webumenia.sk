@extends('layouts.admin')

@section('title')
kolekcie |
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Kolekcie</h1>

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
                <a href="{!! route('collection.create') !!}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Vytvoriť</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Názov</th>
                            <th>Autor</th>
                            <th>Počet diel</th>
                            <th>Dátum</th>
                            <th>Publikovať</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($collections as $i)
			            <tr>
			                <td>{!! $i->id !!}</td>
                            <td>{!! $i->name !!}</td>
			                <td>{!! $i->user->name !!}</td>
			                <td>{!! $i->items()->count(); !!}</td>
                            <td>@datetime($i->created_at)</td>
                            <td class="tw-text-center">
                                @if($i->published_at)
                                    <i class="fa fa-check tw-text-green-800"></i><br/>
                                    <small>od @dateShort($i->published_at)</small>
                                @endif
                            </td>
			                <td>
                                {!! link_to_action('App\Http\Controllers\CollectionController@show', 'Detail', array($i->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) !!}
                                {!! link_to_action('App\Http\Controllers\CollectionController@edit', 'Upraviť', array($i->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                {!! link_to_action('App\Http\Controllers\ItemController@index', 'Diela', ['collection_id' => $i->id], array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                <a href="{!! $i->getUrl() !!}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>
                                <x-admin.link-with-confirmation
                                    action="{{ route('collection.destroy', $i->id) }}" method="DELETE"
                                    class="btn btn-danger btn-xs btn-outline"
                                    message="Naozaj to chceš zmazať?">
                                    Zmazať
                                </x-admin.link-with-confirmation>

                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="tw-text-center"><?php echo $collections->render(); ?></div>


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop
