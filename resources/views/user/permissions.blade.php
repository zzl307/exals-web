@extends('common.layouts')

@section('menu')
	权限列表
@stop

@section('content')

	@include('common.message')
	
	<div class="row">
		<div class="col-md-12">
			<div class="widget box">
				<div class="widget-header">
					<h4>
						<i class="icon-reorder">
						</i>
						权限列表
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
							<a data-toggle="modal" href="#addPermissionModal">
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
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th class="col-md-2">
									权限定义
								</th>
								<th class="col-md-2">
									名称
								</th>
								<th>
									描述
								</th>
								<th class="col-md-1">
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($permissions as $vo)
								<tr>
									<td>
									   {{$vo->keyword}}
									</td>
									<td>
										{{$vo->name}}
									</td>
									<td class="col-md-3">
										{{$vo->description}}
									</td>
									<td>
										<a href="javascript:;" onclick="editPermission('{{ $vo->id }}', '{{ $vo->keyword }}', '{{ $vo->name }}', '{{ $vo->description }}')" title="修改权限内容">
											<i class="icon-edit">
											</i>
										</a>
										&nbsp;
										<a href="{{ url('user/permission/delete?id='.$vo->id) }}" onclick="if(confirm('确定删除权限') == false) return false;" title="删除">
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

	<div class="modal fade" id="addPermissionModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						新增权限定义
					</h4>
				</div>
				<form class="form-horizontal row-border" action="{{ url('user/permission/add') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								权限定义
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="keyword" required autofocus>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">
								名称
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="name" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">
								描述
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="description">
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

	<div class="modal fade" id="editPermissionModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						修改权限属性
					</h4>
				</div>
				<form class="form-horizontal row-border" action="{{ url('user/permission/update') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-body">
						<input id="editPermissionModal_id" type="hidden" class="form-control" name="id">
						<div class="form-group">
							<label for="name" class="col-md-3 control-label">
								权限定义
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input id="editPermissionModal_keyword" type="text" class="form-control" name="keyword" required readonly>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-md-3 control-label">
								名称
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input id="editPermissionModal_name" type="text" class="form-control" name="name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-md-3 control-label">
								描述
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input id="editPermissionModal_desc" type="text" class="form-control" name="description">
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
	<script type="text/javascript">
		function editPermission(id, keyword, name, desc)
		{
			$('#editPermissionModal_id').val(id);
			$('#editPermissionModal_keyword').val(keyword);
			$('#editPermissionModal_name').val(name);
			$('#editPermissionModal_desc').val(desc);
			$('#editPermissionModal').modal();
		}
	</script>
@stop
