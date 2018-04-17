@extends('common.layouts')

@section('menu')
	终端记录查询
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
						终端记录查询
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
                                <i class="icon-refresh">
                                </i>
                            </span>
						</div>
					</div>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="" method="get">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-2">
								<input type="text" class="form-control datepicker" name="date" value="{{ isset($data) ? $data['date'] : date('Y-m-d', time()) }}" placeholder="日期">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="site_id" class="form-control" name="site_id" value="{{ isset($data) ? $data['site_id'] : '' }}" type="text" placeholder="场所号">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="mac" class="form-control" name="mac" value="{{ isset($data) ? $data['mac'] : '' }}" type="text" placeholder="终端MAC">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="user_id" class="form-control" name="user_id" value="{{ isset($data) ? $data['user_id'] : '' }}" type="text" placeholder="用户身份">
							</div>
							<div class="col-md-1" style="margin-left: -15px;">
								<select id="action" class="form-control" name="action">
									<option value="-1" selected>全部</option>
									<option value="0" {{ isset($data) && $data['action'] == 0 ? 'selected' : '' }}>查询</option>
									<option value="100" {{ isset($data) && $data['action'] == 100 ? 'selected' : '' }}>出现</option>
									<option value="1" {{ isset($data) && $data['action'] == 1 ? 'selected' : '' }}>登录</option>
									<option value="2" {{ isset($data) && $data['action'] == 2 ? 'selected' : '' }}>登出</option>
								</select>
							</div>
							<div class="col-md-1">
								<button class="btn btn-sm" style="padding: 5px 16px;margin-left: -15px;">搜索</button>
							</div>
						</div>
					</form>
				</div>

				@if(isset($errmsg))
					<div class="alert alert-danger fade in">
					<i class="icon-remove close" data-dismiss="alert">
					</i>
					<strong>
						错误: 
					</strong>
					{{ $errmsg }}
				</div>
				@endif

				@if (!empty($logs))
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th>
										时间
									</th>
									<th>
										动作
									</th>
									<th>
										审计设备
									</th>
									<th>
										场所号
									</th>
									<th>
										终端MAC
									</th>
									<th>
										终端IP
									</th>
									<th>
										用户身份
										</th>
									<th>
										身份类型
									</th>
									<th>
										获取方式
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($logs as $vo)
									<tr>
										<td>
											{{ $vo->time }}
										</td>
										<td>
											@if ($vo->action == 0)
												查询
											@elseif ($vo->action == 1)
												<span class="label label-success">登录</span>
											@elseif ($vo->action == 2)
												<span class="label label-default">登出</span>
											@elseif ($vo->action == 100)
												<span class="label label-warning">出现</span>
											@endif
										</td>
										<td>
											{{ $vo->device_id }}
										</td>
										<td>
											{{ $vo->site_id }}
										</td>
										<td>
											{{ $vo->mac }}
										</td>
										<td>
											{{ $vo->local_ip }}
										</td>
										<td>
											{{ $vo->user_id }}
										</td>
										<td>
											{{ $vo->id_type }}
										</td>
										<td>
											@if ($vo->action == 1)
												@if ($vo->method == 1)
													反查
												@elseif ($vo->method == 2)
													消息
												@elseif ($vo->method == 3)
													Portal截取
												@elseif ($vo->method == 4)
													Radius认证
												@elseif ($vo->method == 5)
													第三方
												@elseif ($vo->method == 6)
													中心反查
												@elseif ($vo->method == 7)
													Radius计费
												@elseif ($vo->method == 8)
													缓存
												@else
													未知
												@endif
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="row">
							<div class="table-footer">
								<div class="col-md-12">
									{{$logs->appends([
														'date' => isset($data['date']) ? $data['date'] : '',
														'site_id' => isset($data['site_id']) ? $data['site_id'] : '',
														'mac' => isset($data['mac']) ? $data['mac'] : '',
														'user_id' => isset($data['user_id']) ? $data['user_id'] : '',
														'action' => isset($data['action']) ? $data['action'] : ''
													 ])->links()}}
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

	<div class="modal fade" id="editMirrorModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						修改数据镜像接口
					</h4>
				</div>

				<form class="form-horizontal row-border" action="{{ url('system/mirror') }}" method="post">

					{{ csrf_field() }}
					
					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								名称
							</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="mirror[mirror-device])" value="{{ isset($systemConfig['mirror-device']) ? $systemConfig['mirror-device'] : old('mirror')['mirror-device'] }}" required autofocus>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							取消
						</button>
						<button type="submit" class="btn btn-primary">
							确定
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@stop

@section('javascript')
    <script type="text/javascript" src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".datepicker").datepicker({
                inline: true,
                defaultDate: +7,
                showOtherMonths: true,
                autoSize: true,
                dateFormat: "yy-mm-dd"
            });
        });
	</script>
@stop
