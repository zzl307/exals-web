@extends('common.layouts')

@section('menu')
	感知数据查询
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
						感知数据查询
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
								<input id="epmac" class="form-control" name="epmac" value="{{ isset($data) ? $data['epmac'] : '' }}" type="text" placeholder="终端MAC">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="apmac" class="form-control" name="apmac" value="{{ isset($data) ? $data['apmac'] : '' }}" type="text" placeholder="AP MAC">
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
										APMAC
									</th>
									<th>
										终端MAC
									</th>
									<th>
										终端类型
									</th>
									<th>
										通道
									</th>
									<th>
										关联
									</th>
									<th>
										BSSID
									</th>
									<th>
										rssi
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
											{{ $vo->ap_mac }}
										</td>
										<td>
											{{ $vo->ep_mac }}
										</td>
										<td>
											{{ $vo->ep_type }}
										</td>
										<td>
											{{ $vo->channel }}
										</td>
										<td>
											{{ $vo->associated }}
										</td>
										<td>
											{{ $vo->bssid }}
										</td>
										<td>
											{{ $vo->rssi }}
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
														'epmac' => isset($data['epmac']) ? $data['epmac'] : '',
														'apmac' => isset($data['apmac']) ? $data['apmac'] : '',
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
