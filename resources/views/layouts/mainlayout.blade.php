<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CEE-WELL</title>
    <!-- Favicon-->
    <link rel="icon" href="{!! asset('theme/favicon.ico') !!}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{!! asset('theme/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{!! asset('theme/plugins/node-waves/waves.css') !!}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{!! asset('theme/plugins/animate-css/animate.css') !!}" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="{!! asset('theme/plugins/morrisjs/morris.css') !!}" rel="stylesheet" />

      <!-- JQuery DataTable Css -->
    <link href="{!! asset('theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') !!}" rel="stylesheet">


    <!-- Custom Css -->
    <link href="{!! asset('theme/css/style.css') !!}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{!! asset('theme/css/themes/all-themes.css') !!}" rel="stylesheet" />
</head>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
   
     @include('layouts.header')
   
     @include('layouts.sidebar')


    <section class="content">
          <!-- [ breadcrumb ] start -->
        @include('layouts.notifications')
         @yield('content')
    </section>

    <!-- Jquery Core Js -->
    <script src="{!! asset('theme/plugins/jquery/jquery.min.js') !!}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{!! asset('theme/plugins/bootstrap/js/bootstrap.js') !!}"></script>

    <!-- Select Plugin Js -->
    <script src="{!! asset('theme/plugins/bootstrap-select/js/bootstrap-select.js') !!}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{!! asset('theme/plugins/jquery-slimscroll/jquery.slimscroll.js') !!}"></script>

    <!-- Validator Plugin Js -->
    <script src="{!! asset('theme/plugins/jquery-validation/jquery.validate.js') !!}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{!! asset('theme/plugins/node-waves/waves.js') !!}"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{!! asset('theme/plugins/jquery-countto/jquery.countTo.js') !!}"></script>

    <!-- Morris Plugin Js -->
    <script src="{!! asset('theme/plugins/raphael/raphael.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/morrisjs/morris.js') !!}"></script>

    <script src="{!! asset('theme/plugins/jquery-datatable/jquery.dataTables.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/jszip.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/pdfmake.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/vfs_fonts.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/jquery-datatable/extensions/export/buttons.print.min.js') !!}"></script>

      
    <script src="{!! asset('theme/js/pages/tables/jquery-datatable.js') !!}"></script>

    <!-- Demo Js -->
    <script src="{!! asset('theme/js/demo.js') !!}"></script>

    <!-- ChartJs -->
    <script src="{!! asset('theme/plugins/chartjs/Chart.bundle.js') !!}"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{!! asset('theme/plugins/flot-charts/jquery.flot.js') !!}"></script>
    <script src="{!! asset('theme/plugins/flot-charts/jquery.flot.resize.js') !!}"></script>
    <script src="{!! asset('theme/plugins/flot-charts/jquery.flot.pie.js') !!}"></script>
    <script src="{!! asset('theme/plugins/flot-charts/jquery.flot.categories.js') !!}"></script>
    <script src="{!! asset('theme/plugins/flot-charts/jquery.flot.time.js') !!}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{!! asset('theme/plugins/jquery-sparkline/jquery.sparkline.js') !!}"></script>

    <!-- Custom Js -->
    <script src="{!! asset('theme/js/admin.js') !!}"></script>
    <script src="{!! asset('theme/js/pages/index.js') !!}"></script>

     <!-- Custom Js -->
     @yield('js')

</body>

</html>
