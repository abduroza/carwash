<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Car WASH</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 4.5.0 -->
        <link rel="stylesheet" href="{{ asset('bootstrap-4.5.0/dist/css/bootstrap.min.css') }}">
        {{-- custom css --}}
        <link rel="stylesheet" href="{{ asset('mycss/style.css') }}">
        <!-- Font Awesome 4.7.0 -->
        <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
        <!-- Ionicons 2.0.1-->
        <link rel="stylesheet" href="{{ asset('ionicons/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('linearicon/linearicon.min.css') }}">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


        {{-- <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
        <script src="{{ mix('js/app.js') }}" type="text/javascript" defer></script> --}}
    </head>
    
        </div>
    <body class="bg-light">
        <div id="appku">
            <app></app>
        </div>

        <!-- jQuery 3.5.1 -->
        <script src="{{ asset('jquery/jquery-3.5.1.min.js') }}"></script>
        <!-- Bootstrap  -->
        <script src="{{ asset('bootstrap-4.5.0/dist/js/bootstrap.min.js') }}"></script>
        <!-- SlimScroll -->
        {{-- <script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script> --}}
        <!-- FastClick -->
        {{-- <script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script> --}}

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
<!-- Perhatikan tag <app></app>, dimana tag tersebut merupakan custom tag yang dibuat dari component Vue.js dan pastikan tag div yang mengapitnya memiliki id appku. Adapun tag lainnya hanya me-load file js dan css. -->
