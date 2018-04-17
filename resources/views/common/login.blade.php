<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('static/css/main.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/plugins.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/responsive.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('static/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/login.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('static/css/fontawesome/font-awesome.min.css')}}">
        <!--[if IE 7]>
            <link rel="stylesheet" href="{{asset('static/css/fontawesome/font-awesome-ie7.min.css')}}">
        <![endif]-->
        <!--[if IE 8]>
            <link href="{{asset('static/css/ie8.css')}}" rel="stylesheet" type="text/css" />
        <![endif]-->
    </head>
    
    <body class="login">
        <div class="logo">
            <img src="{{asset('static/img/logo.png')}}" alt="logo" />
            <strong>
                exands 审计数据平台
            </strong>
        </div>
        
        @section('content')

        @show
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
