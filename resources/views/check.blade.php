<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
        <title>exands 审计数据验证 @yield('title')</title>
        <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="{{asset('plugins/jquery-ui/jquery.ui.1.10.2.ie.css')}}"/>
        <![endif]-->
        <link href="{{asset('static/css/main.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/plugins.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('static/css/responsive.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('static/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('static/css/fontawesome/font-awesome.min.css')}}">
        <link href="{{asset('static/css/simditor.css')}}" rel="stylesheet" type="text/css"/>
        <!--[if IE 7]>
            <link rel="stylesheet" href="{{asset('static/css/fontawesome/font-awesome-ie7.min.css')}}">
        <![endif]-->
        <!--[if IE 8]>
            <link href="{{asset('static/css/ie8.css')}}" rel="stylesheet" type="text/css" />
        <![endif]-->
        @section('style')
            
        @show
    </head>
    
    <body>
        <div class="container">
            <div style='height: 12px'></div>
            <div class="col-md-12">

                @include('common.message')
                
                @if(!empty($userLog))
                    <div class="widget box">
                        <div class="widget-header">
                            <div style="height: 16px;"></div>
                            <h4>
                                <p>终端MAC: {{ $data['client'] }}</p>
                                <p>审计设备: {{ $data['device_id'] }}</p>
                            </h4>
                        </div>
                        <div class="widget-content">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            时间
                                        </th>
                                        <th>
                                            身份
                                        </th>
                                        <th>
                                            类型
                                        </th>
                                        <th>
                                            获取
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userLog as $vo)
                                    <tr>
                                        <td>
                                            {{ $vo->time }}
                                        </td>
                                        <td>
                                            {{ $vo->user_id }}
                                        </td>
                                        <td>
                                            {{ $vo->id_type }}
                                        </td>
                                        <td>
                                            @if($vo->method == 0)
                                                
                                            @elseif($vo->method == 1)
                                                反查
                                            @elseif($vo->method == 2)
                                                消息
                                            @elseif($vo->method == 3)
                                                Portal截取
                                            @elseif($vo->method == 4)
                                                Radius认证
                                            @elseif($vo->method == 5)
                                                第三方
                                            @elseif($vo->method == 6)
                                                中心反查
                                            @elseif($vo->method == 7)
                                                Radius计费
                                            @elseif($vo->method == 8)
                                                缓存
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @include('common.commonJs')
        
        @section('javascript')

        @show    

    </body>
</html>
