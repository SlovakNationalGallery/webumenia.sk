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
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        {{ HTML::style('css/sb-admin.css') }}
        {{ HTML::style('css/ladda-themeless.min.css') }}
        {{ HTML::style('css/bootstrap-wysihtml5.css') }}
        {{ HTML::script('js/modernizr.custom.js') }}

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
                <a class="navbar-brand" href="{{ URL::to('admin') }}">WEBUMENIA Admin</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Profil</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Nastavenia</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Odhlásiť</a>
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
                            {{ Form::open(['url' => 'search',  'method' => 'get']) }}
                            <div class="input-group custom-search-form">
                                {{ Form::text('search', @$search, array('class' => 'form-control', 'placeholder' => 'Hľadať...')) }}
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                            {{Form::close() }}
                        </li>
                        <li>
                            <a href="{{ URL::to('admin') }}"><i class="fa fa-dashboard fa-fw"></i> Úvod</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('item') }}"><i class="fa fa-picture-o fa-fw"></i> Diela</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('collection') }}"><i class="fa fa-th-list fa-fw"></i> Kolekcie</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('harvests') }}"><i class="fa fa-download fa-fw"></i> Spice Harvester</a>
                        </li>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/bootstrap-datepicker.js') }}
    {{ HTML::script('js/spin.min.js') }}
    {{ HTML::script('js/ladda.min.js') }}
    {{ HTML::script('js/wysihtml5-0.3.0.min.js') }}
    {{ HTML::script('js/bootstrap3-wysihtml5.js') }}

    <script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            language: "sk"
        });

        Ladda.bind( '.ladda-button');

        $('.wysiwyg').wysihtml5({
            "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": true, //Button which allows you to edit the generated HTML. Default false
            "link": false, //Button to insert a link. Default true
            "image": false, //Button to insert an image. Default true,
            "color": false, //Button to change color of font  
            "blockquote": false, //Blockquote  
            "size": 'sm' //default: none, other options are xs, sm, lg
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

    });
    </script>


</body>
</html>
