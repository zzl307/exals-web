@extends('common.layouts')

@section('style')
	<style type="text/css">
		code{
			display: block;
			float: left;
			padding: 0 8px;
			margin: 5px 5px 5px 5px;
			line-height: 23px;
			font-size: 11px;
			border: 0px;
			background: #fff;
		}
	</style>
@stop

@section('menu')
	场所记录查询
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
						场所记录查询
					</h4>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="" method="get">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-2">
								<input type="text" class="form-control datepicker" name="date" value="{{ isset($data) ? $data['date'] : date('Y-m-d', time()) }}" placeholder="日期">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="site_id" class="form-control" name="site_id" value="{{ isset($data) ? $data['site_id'] : '' }}" type="text" required placeholder="场所号">
							</div>
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
					</form>
				</div>
				
				@if(!isset($data))
					<div class="widget-content no-padding">
						<code>
							TODO: 显示一些历史记录!
						</code>
					</div>
				@elseif(empty($logs))
					<div class="widget-content no-padding">
						<code>
							没有找到适合条件的历史记录!
						</code>
						<code>
							<a href="javascript:history.back(-1)">
								返回
							</a>
						</code>
					</div>
				@else
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th>
										时间
									</th>
									<th>
										场所号
									</th>
									<th>
										审计设备
									</th>
									<th>
										公网IP
									</th>
									<th>
										软件版本
									</th>
									<th>
										在线终端数
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
											{{ $vo->site_id }}
										</td>
										<td>
											{{ $vo->device_id }}
										</td>
										<td>
											{{ $vo->ip_address }}
										</td>
										<td>
											{{ $vo->version }}
										</td>
										<td>
											{{ $vo->online_users }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="row">
							<div class="table-footer">
								<div class="col-md-6">
								</div>
								<div class="col-md-6">
									{{$logs->appends([
														'site_id' => isset($data['site_id']) ? $data['site_id'] : '',
														'mac' => isset($data['mac']) ? $data['mac'] : '',
														'user_id' => isset($data['user_id']) ? $data['user_id'] : '',
														'date' => isset($data['date']) ? $data['date'] : ''
													 ])->links()}}
								</div>
							</div>
						</div>
					</div>
				@endif
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
