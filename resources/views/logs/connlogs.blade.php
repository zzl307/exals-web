@extends('common.layouts')

@section('menu')
	上网日志查询
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
						上网日志查询
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
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
					</form>
				</div>

				@if(isset($errmsg))
					<div class="alert alert-danger fade in">
					<i class="icon-remove close" data-dismiss="alert"></i>
					<strong>{{ $errmsg }}</strong>
				</div>
				@endif
				
				@if(!empty($logs))
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
										终端MAC
									</th>
									<th>
										本地IP
									</th>
									<th>
										本地端口
									</th>
									<th>
										公网IP
									</th>
									<th>
										公网端口
									</th>
									<th>
										服务器IP
									</th>
									<th>
										服务器端口
									</th>
									<th>
										协议名称
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
											{{ $vo->mac }}
										</td>
										<td>
											{{ $vo->local_ip }}
										</td>
										<td>
											{{ $vo->local_port }}
										</td>
										<td>
											{{ $vo->public_ip }}
										</td>
										<td>
											{{ $vo->public_port }}
										</td>
										<td>
											{{ $vo->remote_ip }}
										</td>
										<td>
											{{ $vo->remote_port }}
										</td>
										<td>
											@if($vo->proto == 1)
												ICMP
											@elseif($vo->proto == 6)
												TCP
											@else
												UDP
											@endif
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
