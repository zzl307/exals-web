<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"
        />
        <title>
            exands 设备配置平台
        </title>
        <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"
        />
        <link href="{{asset('static/css/main.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/plugins.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/responsive.css')}}" rel="stylesheet" type="text/css"
        />
        <link href="{{asset('static/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/error.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('static/css/fontawesome/font-awesome.min.css')}}">
        <!--[if IE 7]>
            <link rel="stylesheet" href="{{asset('static/css/fontawesome/font-awesome-ie7.min.css')}}">
        <![endif]-->
        <!--[if IE 8]>
            <link href="{{asset('static/css/ie8.css')}}" rel="stylesheet" type="text/css" />
        <![endif]-->
        <script type="text/javascript" src="{{asset('static/js/libs/jquery-1.10.2.min.js')}}">
        </script>
        <script type="text/javascript" src="{{asset('bootstrap/js/bootstrap.min.js')}}">
        </script>
        <script type="text/javascript" src="{{asset('static/js/libs/lodash.compat.min.js')}}">
        </script>
        <!--[if lt IE 9]>
            <script src="{{asset('static/js/libs/html5shiv.js')}}">
            </script>
        <![endif]-->
    </head>
    
    <body class="error">
        <div class="title">
            <h1>
                404
            </h1>
        </div>
        <div class="actions">
            <div class="list-group">
                <li class="list-group-item list-group-header align-center">
                    {{ $exception->getMessage() }}
                </li>
                <a href="{{ url('/') }}" class="list-group-item">
                    <i class="icon-home">
                    </i>
                    返回
                    <i class="icon-angle-right align-right">
                    </i>
                </a>
            </div>
        </div>
        <div class="footer">
            exands &amp; 设备配置平台
            <br>
            &copy; 2018
        </div>
    </body>

</html>