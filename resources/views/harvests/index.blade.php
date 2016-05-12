@extends('layouts.admin')

@section('title')
Spice Harvester | 
@parent
@stop

@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Spice Harvester</h1>

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
                <a href="{!! route('harvests.create') !!}" class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> Pridať</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            	<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Typ</th>
                            <th>URL</th>
                            <th>Set</th>
                            <th>Metadata Prefix</th>
                            <th>Status</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach($harvests as $h)
			            <tr>
			                <td>{!! $h->id !!}</td>
                            <td>{!! $h::$types[$h->type] !!}</td>
			                <td>{!! $h->base_url !!}</td>
			                <td>{!! $h->set_name !!}</td>
			                <td>{!! $h->metadata_prefix !!}</td>
			                <td>{!! $h->status . '<br>' . date("d. m. Y h:m",strtotime($h->updated_at)) !!}</td>
			                <td>
                                {!! Form::open(array('method' => 'DELETE', 'route' => array('harvests.destroy', $h->id), 'class' => 'visible-xs-inline form-inline')) !!}
                                {!! link_to_action('SpiceHarvesterController@show', 'Detail', array($h->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) !!} 
                                {!! link_to_action('SpiceHarvesterController@edit', 'Upraviť', array($h->id), array('class' => 'btn btn-primary btn-xs btn-outline')) !!}
                                    {!! Form::submit('Zmazať', array('class' => 'btn btn-danger btn-xs btn-outline')) !!}
                                {!! Form::close() !!}
                                <br>
                                {!! link_to_action('SpiceHarvesterController@orphaned', 'Zobraziť odobrané zo setu', array($h->id), array('class' => 'btn btn-danger ladda-button', 'data-style'=>'expand-right')) !!}
                                {!! link_to_action('SpiceHarvesterController@launch', 'Reindex', array($h->id, 'reindex'=>true), array('class' => 'btn btn-default ladda-button', 'data-style'=>'expand-right')) !!} 
                                {!! link_to_action('SpiceHarvesterController@launch', 'Spustiť', array($h->id), array('class' => 'btn btn-primary ladda-button', 'data-style'=>'expand-right')) !!} 
                            </td>
			            </tr>
						@endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    {!! $harvests->appends(@Input::except('page'))->render() !!}
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


@stop