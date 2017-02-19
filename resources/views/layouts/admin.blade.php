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
        {!! Html::style('css/sb-admin.css') !!}
        {!! Html::style('css/ladda-themeless.min.css') !!}
        {!! Html::style('css/bootstrap-wysihtml5.css') !!}
        {!! Html::style('css/bootstrap-colorpicker.min.css') !!}
        {!! Html::style('css/plugins/selectize.css') !!}
        {!! Html::style('css/plugins/selectize.bootstrap3.css') !!}
        {!! Html::style('css/plugins/bootstrap-switch.css') !!}
        {!! Html::script('js/modernizr.custom.js') !!}

</head>

<body>

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
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
                        <i class="fa fa-user fa-fw"></i>  {!! Auth::user()->name !!} <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Profil</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Nastavenia</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{!! URL::to('logout') !!}"><i class="fa fa-sign-out fa-fw"></i> Odhlásiť</a>
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
                            <?php $search_request = (Request::is( 'authority')) ? 'authority' : 'item' ?>
                            {!! Form::open(['url' => $search_request.'/search',  'method' => 'get']) !!}
                            <div class="input-group custom-search-form">
                                {!! Form::text('search', @$search, array('class' => 'form-control', 'placeholder' => 'Hľadať...')) !!}
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                            {!!Form::close() !!}
                        </li>
                        <li>
                            <a href="{!! URL::to('admin') !!}"><i class="fa fa-dashboard fa-fw"></i> Úvod</a>
                        </li>
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('item') !!}"><i class="fa fa-picture-o fa-fw"></i> Diela</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('authority') !!}"><i class="fa fa-user fa-fw"></i> Autority</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole(['admin', 'editor']))
                        <li>
                            <a href="{!! URL::to('collection') !!}"><i class="fa fa-th-list fa-fw"></i> Kolekcie</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('article') !!}"><i class="fa fa-newspaper-o fa-fw"></i> Články</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('slide') !!}"><i class="fa fa-exchange fa-fw"></i> Carousel</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('sketchbook') !!}"><i class="fa fa-book fa-fw"></i> Skicáre</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole(['admin', 'import']))
                        <li>
                            <a href="#"><i class="fa fa-download fa-fw"></i> Import<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                @if (Entrust::hasRole(['admin']))
                                <li>
                                    <a href="{!! URL::to('harvests') !!}">Spice Harvester</a>
                                </li>
                                @endif                                
                                <li>
                                    <a href="{!! URL::to('imports') !!}">CSV Import</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('user') !!}"><i class="fa fa-male fa-fw"></i> Užívatelia</a>
                        </li>
                        @endif
                        @if (Entrust::hasRole('admin'))
                        <li>
                            <a href="{!! URL::to('logs') !!}" target="_blank"><i class="fa fa-exclamation-triangle fa-fw"></i> Logy</a>
                        </li>
                        @endif
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
        <div class="modal-dialog">
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
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


    <script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            language: "sk"
        });

        Ladda.bind( '.ladda-button');

        var csrf = '{!!csrf_token()!!}';
        $( '.wysiwyg' ).ckeditor({
            language: 'sk',
            filebrowserUploadUrl: '/uploader?csrf_token='+csrf
        });


        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('.btn-detail').click(function( event ){  
            $('#detailModal').modal({modal:true,remote:($(this).attr('href'))});
            event.preventDefault();
        });    


        // select all feature
        $('#selectall').click(function () {
            console.log('vyselektuj vsekty!');
            $('.selectedId').prop('checked', this.checked);
        });

        $('.selectedId').change(function () {
            var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
            $('#selectall').prop("checked", check);
        });   


        var links_count = $('.form_link').length;

        $("a#add_link").click(function(e){
            e.preventDefault();
            $('#external_links').append('<div class="col-md-5"><div class="form-group"><label for="url">URL</label> <input class="form-control linkpicker" placeholder="http://" name="links['+links_count+'][url]" type="text">  </div></div>');
            $('#external_links').append('<div class="col-md-5"><div class="form-group"><label for="label">Zobrazená adresa</label><input class="form-control" placeholder="wikipédia" name="links['+links_count+'][label]" type="text"></div></div>');
            links_count++;
        });

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
