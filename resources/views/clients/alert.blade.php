@extends('common.layouts')

@section('menu')
	敏感人员
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
						敏感人员
					</h4>
					@can('client_alert_manage')
						<div class="toolbar no-padding">
							<div class="btn-group">
								<a href="{{ url('client/alert/add') }}">
									<span class="btn btn-xs">
										<i class="icon-plus"></i>
										新增
									</span>
								</a>	
							</div>
						</div>
					@endcan
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="" method="GET">
						<div class="form-group" style="margin-top: 10px;">
   							<div class="col-md-4">
								<input type="text" class="form-control" name="user" value="{{ old('user') }}" placeholder="用户名 / 终端MAC">
							</div>
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
					</form>
				</div>
				
				@if(isset($alerts) && !empty($alerts->items))
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th class="col-md-2">设置时间</th>
									<th class="col-md-1">敏感用户</th>
									<th class="col-md-4">原因</th>
									<th class="col-md-2">到期时间</th>
									<th class="col-md-1">有效状态</th>
									<th class="col-md-1">监控数据</th>
									@can('client_alert_manage')
										<th></th>
									@endcan
								</tr>
							</thead>
							<tbody>
								@foreach($alerts as $vo)
									<tr>
										<td>
											{{ $vo->time }}
										</td>
										<td>
											<a href="{{ url('client/alarm?user='.$vo->user) }}">
												{{ $vo->user }}
											</a>
										</td>
										<td>
											{{ $vo->reason }}
										</td>
										<td>
											@if(strtotime($vo->expiry) > 0)
												{{ $vo->expiry }}
											@endif
										</td>
										<td>
											@if($vo->valid == 1)
												有效
											@else
												<span class="label label-warning">
													无效
												</span>
											@endif
										</td>
										<td>
											{{ $vo->data ? '是' : '否' }}
										</td>
										@can('client_alert_manage')
											<td>
												<a href="{{ url('client/alert/add?update=1&user='.$vo->user.'&reason='.$vo->reason.'&expiry='.$vo->expiry.'&data='.$vo->data) }}" class="bs-tooltip" title="修改">
													<i class="icon-edit"></i>
												</a>

												<a href="" onclick="if(confirm('确定删除?') == false) return false; event.preventDefault(); document.getElementById('delAlert_user').value = '{{ $vo->user }}'; document.getElementById('delAlert_form').submit();" class="bs-tooltip" title="删除">
													<i class="icon-remove"></i>
												</a>
											</td>
										@endcan
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="row">
							<div class="table-footer">
								<div class="col-md-6">
								</div>
								<div class="col-md-6">
									{{ $alerts->render() }}
								</div>
							</div>
						</div>
					</div>

					<form id="delAlert_form" action="{{ route('delAlert') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input id="delAlert_user" type="hidden" class="form-control" name="user">
					</form>
				@endif

			</div>
		</div>
	</div>
@stop
