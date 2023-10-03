@extends('layouts.admin')

@section('title')
    @parent
    - diela
@stop

@section('content')


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Skicáre</h1>

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
                    <a href="{!! route('sketchbook.create') !!}" class="btn btn-primary btn-outline"><i
                            class="fa fa-plus"></i> Vytvoriť</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Poradie</th>
                                <th>Náhľad</th>
                                <th>ID</th>
                                <th>Názov</th>
                                <th>Vygenerovaný</th>
                                <th>Publikovaný</th>
                                <th>Akcie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sketchbooks as $i)
                                <tr>
                                    <td>{!! $i->order !!}</td>
                                    <td class="tw-text-center"><img src="{!! $i->item->getImagePath() !!}" alt=""
                                            class="img-responsive nahlad"></td>
                                    <td><a href="{!! $i->item->getUrl() !!}"
                                            target="_blank">{!! $i->item_id !!}</a></td>
                                    <td>{!! $i->title !!}</td>
                                    <td>
                                        {!! $i->generated_at !!}
                                        @if ($i->file)
                                            <br>{!! formatBytes($i->fileSize) !!}
                                        @endif
                                    </td>
                                    <td>{!! $i->publish !!}</td>
                                    <td>
                                        {!! link_to_action('App\Http\Controllers\SketchbookController@edit', 'Upraviť', [$i->id], ['class' => 'btn btn-primary btn-xs btn-outline']) !!}
                                        <x-admin.link-with-confirmation
                                            action="{{ route('sketchbook.destroy', $i->id) }}"
                                            method="DELETE" class="btn btn-danger btn-xs btn-outline"
                                            message="Naozaj to chceš zmazať?">
                                            Zmazať
                                        </x-admin.link-with-confirmation>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->


@stop
