@extends('common.layouts')

@section('menu')
	终端实名信息查询
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
						终端实名信息查询
					</h4>
					@can('client_uid_manage')
						<div class="toolbar no-padding">
							<div class="btn-group">
								<a href="{{ route('showAddClientForm') }}">
									<span class="btn btn-xs">
										<i class="icon-plus">
										</i>
										新增
									</span>
								</a>
							</div>
						</div>
					@endcan
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal" action="" method="get">
						<div class="form-group" style="margin-top: 15px;">
							<div class="col-md-4">
								<input id="keyword" class="form-control" name="keyword" value="{{ old('keyword') }}" type="text" placeholder="用户名 / 终端MAC" required>
							</div>
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
						@can('client_uid_manage')
							<div class="form-group">
								<div class="col-md-4">
									<a href="{{ url('client/list?persist=1') }}" style="font-size: 11px;">
										<i class="icon-search">
										</i>
										显示所有免除删除的终端
									</a>
								</div>
							</div>
						@endcan
					</form>
				</div>

				@if (isset($clients))
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
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
										登陆场所
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($clients as $vo)
									<tr>
										<td class="col-md-2">
											<span>
												{{ $vo->mac }}
											</span>
											<span style="float: right;">
												@can('client_uid_manage')
													<a href="{{ url('client/list/add?update=1&client_mac='.$vo->mac.'&user_id='.$vo->user_id.'&id_type='.$vo->id_type.'&persist='.$vo->persist) }}" class="bs-tooltip" title="修改">
															<i class="icon-edit" style="color: #555;">
															</i>
													</a>
													<a href="javascript:;" onclick="if(confirm('确定删除?') == false) return false; event.preventDefault(); document.getElementById('delClient_client_mac').value = '{{ $vo->mac }}'; document.getElementById('delClient_form').submit();" class="bs-tooltip" title="删除">
														<i class="icon-trash" style="color: #555;">
														</i>
													</a>
												@endcan
											</span>
										</td>
										<td>
											{{ $vo->user_id }}
										</td>
										<td>
											{{ $vo->id_type }}
										</td>
										<td>
											{{ $vo->first_login }}
										</td>
										<td>
											{{ $vo->last_login }}
										</td>
										<td>
											{{ $vo->site_id }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<form id="delClient_form" action="{{ route('delClient') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input id="delClient_client_mac" type="hidden" class="form-control" name="client_mac">
					</form>
				@endif

			</div>
		</div>
	</div>
@stop
