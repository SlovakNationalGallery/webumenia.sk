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
                <div class="alert alert-info alert-dismissable"><button type="button"
                        class="close" data-dismiss="alert"
                        aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
            @endif

        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{!! route('harvests.create') !!}" class="btn btn-primary btn-outline"><i
                            class="fa fa-plus"></i> Pridať</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Typ</th>
                                {{-- <th>URL</th> --}}
                                <th>Set</th>
                                {{-- <th>Metadata Prefix</th> --}}
                                <th>Status</th>
                                <th></th>
                                <th>Cron</th>
                                <th class="text-right">Akcie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($harvests as $h)
                                <tr>
                                    <td>{!! $h->id !!}</td>
                                    <td>{!! $h::$types[$h->type] !!}</td>
                                    {{-- <td>{!! $h->base_url !!}</td> --}}
                                    <td>{!! $h->set_name !!}</td>
                                    {{-- <td>{!! $h->metadata_prefix !!}</td> --}}
                                    <td>
                                        {{ $h->updated_at ? $h->updated_at->format('d.m.Y H:i') : '' }}<br>
                                        <span class="h4"><span
                                                class="label label-{!! $h->status_class !!}">{!! $h->status !!}</span></span>
                                    </td>
                                    <td>
                                        {!! nl2br(htmlspecialchars($h->status_messages)) !!}<br>
                                    </td>
                                    <td>{!! $h->cron_status !!}</td>
                                    <td class="text-right">
                                        <x-admin.link-with-confirmation
                                            action="{{ route('harvests.destroy', $h->id) }}"
                                            method="DELETE" class="btn btn-danger btn-xs btn-outline"
                                            message="Naozaj to chceš zmazať?">
                                            Zmazať
                                        </x-admin.link-with-confirmation>
                                        {!! link_to_action('App\Http\Controllers\SpiceHarvesterController@show', 'Detail', [$h->id], ['class' => 'btn btn-primary btn-detail btn-xs btn-outline']) !!}
                                        {!! link_to_action('App\Http\Controllers\SpiceHarvesterController@edit', 'Upraviť', [$h->id], ['class' => 'btn btn-primary btn-xs btn-outline']) !!}
                                        <br>
                                        {!! link_to_action('App\Http\Controllers\SpiceHarvesterController@orphaned', 'Zobraziť odobrané zo setu', [$h->id], ['class' => 'btn btn-danger ladda-button btn-xs', 'data-style' => 'expand-right']) !!}
                                        {!! link_to_action('App\Http\Controllers\SpiceHarvesterController@launch', 'Obnoviť všetky', [$h->id, 'reindex' => true], ['class' => 'btn btn-default ladda-button btn-xs', 'data-style' => 'expand-right']) !!}
                                        {!! link_to_action(
    'App\Http\Controllers\SpiceHarvesterController@harvestFailed',
    'Obnoviť chybné (' .
        $h->records()->failed()->count() .
        ')',
    [$h->id],
    ['class' => 'btn btn-default ladda-button btn-xs', 'data-style' => 'expand-right'],
) !!}
                                        {!! link_to_action('App\Http\Controllers\SpiceHarvesterController@launch', 'Spustiť', [$h->id], ['class' => 'btn btn-primary ladda-button btn-xs', 'data-style' => 'expand-right']) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-center">
                        {!! $harvests->appends(@Request::except('page'))->render() !!}
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
