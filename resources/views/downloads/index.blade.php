@extends('layouts.admin')

@section('title')
stiahnutia |
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Stiahnutia</h1>

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
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Dátum</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($downloads as $i)
			            <tr>
			                <td>{!! $i->id !!}</td>
                            <td>{!! $i->item_id !!}</td>
			                <td>{!! $i->type !!}</td>
			                <td>{!! $i->email !!}</td>
                            <td>{!! $i->created_at !!}</td>
			                <td>
                                {!! link_to_action('DownloadController@show', 'Detail', array($i->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) !!}
                                <a href="{!! $i->item->getUrl() !!}" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center"><?php echo $downloads->render(); ?></div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop