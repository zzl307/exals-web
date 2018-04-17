@extends('common.layouts')

@section('menu')
	派博记录查询
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
						派博记录查询
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
                                <i class="icon-refresh">
                                </i>
                                刷新
                            </span>
						</div>
					</div>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="{{ url('paibo/search') }}" method="get">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-2">
								<input type="text" name="date" class="form-control datepicker" value="{{ $data['date'] or date('Y-m-d', time()) }}" placeholder="日期">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="site_id" class="form-control" name="site_id" value="{{ $data['site_id'] or '' }}" type="text" placeholder="场所号">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="mac" class="form-control" name="mac" value="{{ $data['mac'] or '' }}" type="text" placeholder="终端MAC">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="user_id" class="form-control" name="user_id" value="{{ $data['user_id'] or '' }}" type="text" placeholder="用户身份">
							</div>
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
					</form>
				</div>

				@if (!empty($logs))
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
										用户身份
									</th>
									<th>
										类型
									</th>
									<th>
										回执
									</th>
									<th>
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
											{{ $vo->user_id }}
										</td>
										<td>
											{{ $vo->type }}
										</td>
										<td>
											@if (substr($vo->ack, 0, 4) == "4000")
												<span class="label label-success">正常</span>
											@elseif (empty($vo->ack))
												<span class="label label-info">回执为空</span>
											@else
												<span class="label label-warning">错误</span>
											@endif
										</td>
										<td>
											<a href="{{ url('paibo/log', ['id' => $vo->id, 'date' => $data['date']]) }}" class="btn btn-xs bs-tooltip" title="详情"><i class="icon-eye-open"></i></a>
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
									{!! $logs->appends(Request::except('page'))->render() !!}
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
