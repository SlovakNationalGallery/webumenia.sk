<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="SNG, Igor Rjabinin">

    <title>
        @section('title')
            WEBUMENIA ADMIN
        @show
    </title>


    <!-- CSS are placed here -->
    <script src="https://use.fontawesome.com/73587c90bb.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/tailwind.css') }}" />
    {!! Html::style(mix('/css/admin.css')) !!}
</head>

<body>

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation"
            style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{!! URL::to('admin') !!}">WEBUMENIA Admin</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> {!! Auth::user()->name !!} <i
                            class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Profil</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Nastavenia</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{!! URL::to('logout') !!}"><i class="fa fa-sign-out fa-fw"></i>
                                Odhlásiť</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <?php $search_request = Request::is('authority') ? 'authority' : 'item'; ?>
                            {!! Form::open(['url' => $search_request . '/search', 'method' => 'get']) !!}
                            <div class="input-group custom-search-form">
                                {!! Form::text('search', @$search, ['class' => 'form-control', 'placeholder' => 'Hľadať...']) !!}
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                            {!! Form::close() !!}
                        </li>
                        <li>
                            <a href="{!! URL::to('admin') !!}"><i class="fa fa-dashboard fa-fw"></i>
                                Úvod</a>
                        </li>
                        @can('edit')
                            <li>
                                <a href="{!! URL::to('item') !!}"><i class="fa fa-picture-o fa-fw"></i>
                                    Diela</a>
                            </li>
                        @endcan
                        @can('administer')
                            <li>
                                <a href="{!! URL::to('authority') !!}"><i class="fa fa-user fa-fw"></i>
                                    Autority</a>
                            </li>
                        @endcan
                        @can('edit')
                            <li>
                                <a href="{!! URL::to('collection') !!}"><i class="fa fa-th-list fa-fw"></i>
                                    Kolekcie</a>
                            </li>
                        @endcan
                        @can('edit')
                            <li>
                                <a href="{!! URL::to('article') !!}"><i
                                        class="fa fa-newspaper-o fa-fw"></i> Články</a>
                            </li>
                        @endcan
                        @can('administer')
                            <li
                                class="pl-4 pt-4 tw-uppercase tw-text-sm tw-font-semibold tw-text-gray-600 tw-border-b-0">
                                Homepage
                            </li>
                            <li class="tw-border-b-0">
                                <a href="{{ route('shuffled-items.index') }}"><i
                                        class="fa fa-random fa-fw"></i> Náhodné diela</a>
                            </li>
                            <li class="tw-border-b-0">
                                <a href="{{ route('featured-pieces.index') }}"><i
                                        class="fa fa-newspaper-o fa-fw"></i> Odporúčaný obsah</a>
                            </li>
                            <li class="mb-4 tw-border-b-0">
                                <a href="{{ route('featured-artworks.index') }}"><i
                                        class="fa fa-image fa-fw"></i> Vybrané diela</a>
                            </li>
                        @endcan
                        @can('administer')
                            <li>
                                <a href="{!! URL::to('sketchbook') !!}"><i class="fa fa-book fa-fw"></i>
                                    Skicáre</a>
                            </li>
                        @endcan
                        @can('administer')
                            <li>
                                <a href="{!! route('notices.edit', 1) !!}"><i class="fa fa-bullhorn fa-fw"></i>
                                    Oznamy</a>
                            </li>
                        @endcan
                        @can('administer')
                            <li>
                                <a href="{!! route('redirects.index') !!}"><i
                                        class="fa fa-external-link fa-fw"></i> Presmerovania</a>
                            </li>
                        @endcan
                        @can('import')
                            <li>
                                <a href="#"><i class="fa fa-download fa-fw"></i> Import<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    @can('administer')
                                        <li>
                                            <a href="{!! URL::to('harvests') !!}">Spice Harvester</a>
                                        </li>
                                    @endcan
                                    @can('import')
                                        <li>
                                            <a href="{!! URL::to('imports') !!}">CSV Import</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('administer')
                            <li>
                                <a href="{!! URL::to('user') !!}"><i class="fa fa-male fa-fw"></i>
                                    Užívatelia</a>
                            </li>
                        @endcan
                        @can('administer')
                            <li>
                                <a href="{!! URL::to('logs') !!}" target="_blank"><i
                                        class="fa fa-exclamation-triangle fa-fw"></i> Logy</a>
                            </li>
                        @endcan
                    </ul>
                    <!-- /#side-menu -->
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">

            <!-- Content -->
            @yield('content')

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Modal -->
    <div tabindex="-1" class="modal fade" id="detailModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">×</button>
                    <h3>Modal header</h3>

                </div>
                <div class="modal-body">
                    <p>Modal content here…</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JavaScript Files -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    {!! Html::script('js/bootstrap.min.js') !!}
    {!! Html::script('js/plugins/metisMenu/jquery.metisMenu.js') !!}
    <script src="{!! asset_timed('js/sb-admin.js') !!}"></script>

    {!! Html::script('js/bootstrap-datepicker.js') !!}
    {!! Html::script('js/spin.min.js') !!}
    {!! Html::script('js/ladda.min.js') !!}
    {!! Html::script('ckeditor/ckeditor.js') !!}
    {!! Html::script('ckeditor/adapters/jquery.js') !!}
    {!! Html::script('js/bootstrap-colorpicker.min.js') !!}
    {!! Html::script('js/plugins/Sortable.min.js') !!}
    {!! Html::script('js/plugins/speakingurl.min.js') !!}
    {!! Html::script('js/plugins/bootstrap-switch.min.js') !!}
    {!! Html::script('js/jquery.collection.js') !!}
    {!! Html::script('js/modernizr.custom.js') !!}
    {!! Html::script('js/selectize.min.js') !!}

    <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/admin.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.js-form-collection').collection({
                allow_up: false,
                allow_down: false,
                add_at_the_end: true,
            });

            $('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                language: "sk"
            });

            Ladda.bind('.ladda-button');

            $('.wysiwyg').ckeditor({
                language: 'sk',
                extraAllowedContent: 'iframe[*];*[data-*]{*}(*);*[class]{*}(*)',

                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',

                // Only upload via laravel-filemanager, because its /upload
                // response is incompatible with CKEditor image/file Upload dialogs.
                removeDialogTabs: 'image:Upload;link:upload',
            });


            $('body').on('hidden.bs.modal', '.modal', function() {
                $(this).removeData('bs.modal');
            });

            $('.btn-detail').click(function(event) {
                $('#detailModal').modal({
                    modal: true,
                    remote: ($(this).attr('href'))
                });
                event.preventDefault();
            });


            // select all feature
            $('#selectall').click(function() {
                console.log('vyselektuj vsekty!');
                $('.selectedId').prop('checked', this.checked);
            });

            $('.selectedId').change(function() {
                var check = ($('.selectedId').filter(":checked").length == $(
                    '.selectedId').length);
                $('#selectall').prop("checked", check);
            });


            var links_count = $('.form_link').length;

            $('.colorpicker-component').colorpicker();

            // $('.colorpicker').colorpicker().on('changeColor', function(ev){
            //   // console.log('farba:' + ev.color.toHex());
            //   $(this).prev('span').css('color',ev.color.toHex());
            // });

            $(".switch").bootstrapSwitch();



        });
    </script>
    @yield('script')

</body>

</html>
