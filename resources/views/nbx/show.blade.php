@extends('common.layouts')

@section('menu')
    诺必行记录详情
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

		    @include('common.message')

            <div class="widget box">
                <div class="widget-header">
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        诺必行记录详情
                    </h4>
                </div>
                <div class="widget-content no-padding">
                    <table class="table table-hover table-striped table-bordered table-highlight-head">
                        <tbody>
                            <tr>
                                <td style="width:10%">
                                    <strong>
                                        时间
                                    </strong>
                                </td>
                                <td>
                                    {{ $log->time }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        场所号
                                    </strong>
                                </td>
                                <td>
                                    {{ $log->site_id }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        用户身份
                                    </strong>
                                </td>
                                <td>
                                    {{ $log->user_id }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        终端MAC
                                    </strong>
                                </td>
                                <td>
                                    {{ $log->mac}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        类型
                                    </strong>
                                </td>
                                <td>
                                    {{ $log->type }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        派博服务器
                                    </strong>
                                </td>
                                <td>
                                    {{ $log->server }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        数据内容
                                    </strong>
                                </td>
                                <td style="word-wrap:break-word">
                                    {{ $log->data }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        回执
                                    </strong>
                                </td>
                                <td>
                                    @if (substr($log->ack, 0, 4) == "4000")
                                        <span class="label label-success">正常</span>
                                    @elseif (empty($log->ack))
                                        <span class="label label-info">回执为空</span>
                                    @else
                                        <span class="label label-warning">错误</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border: 1px solid #e5e5e5;">
                <a href="javascript:history.back(-1)">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        返回
                    </button>
                </a>
            </div>
        </div>
    </div>
@stop
