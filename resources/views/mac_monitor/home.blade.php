@extends('common.layouts')

@section('menu')
	终端监控
@stop

@section('content')

	<div class="row row-bg">
		<div class="col-md-12">
			
			@include('common.message')

			<div class="widget box">
				<div class="widget-header" style="border-top: 1px solid #d9d9d9;">
					<h4>
						<i class="icon-reorder">
						</i>
						终端监控
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<a href="{{ url('mac_monitor/add') }}">
								<span class="btn btn-xs">
									<i class="icon-plus">
									</i>
									新增
								</span>
							</a>
						</div>
					</div>
				</div>

				<div class="widget-content no-padding">
					<table class="table table-hover table-striped table-bordered table-highlight-head datatable">
						<thead>
							<tr>
								<th>
									终端MAC
								</th>
								<th>
									实名身份
								</th>
								<th>
									身份类型
								</th>
								<th>
									首次登陆时间
								</th>
								<th>
									最后登陆时间
								</th>
								<th>
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach(\App\MacMonitor::getMonitoredClients() as $vo)
								<tr>
									<td>
										{{ $vo->mac }}
									</td>
									<td>
										{{ isset($vo->user_id) ? $vo->user_id : '' }}
									</td>
									<td>
										{{ isset($vo->id_type) ? $vo->id_type : '' }}
									</td>
									<td>
										{{ isset($vo->first_login) ? $vo->first_login : '' }}
									</td>
									<td>
										{{ isset($vo->last_login) ? $vo->last_login : '' }}
									</td>
									<td>
										<a href="{{ url('mac_monitor/delete', ['mac' => $vo->mac]) }}" class="btn btn-xs bs-tooltip" title="删除" onclick="if(confirm('确定删除?') == false) return false;">
											<i class="icon-trash">
											</i>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop
