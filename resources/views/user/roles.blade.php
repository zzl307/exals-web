@extends('common.layouts')

@section('menu')
	角色列表
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
						用户角色列表
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
							<a data-toggle="modal" href="#addRoleModal">
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
								<th class="col-md-1">
									角色名称
								</th>
								<th class="col-md-2">
									描述
								</th>
								<th class="col-md-3">
									所属权限
								</th>
								<th class="col-md-1">
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($roles as $key => $vo)
								<tr>
									<td>
										{{ $vo['name'] }}
									</td>
									<td>
										{{ $vo['description'] }}
									</td>
									<td>
										{{ $vo['permission'] }}
									</td>
									<td>
										<a href="javascript:;" onclick="setRolePermissions('{{ $key }}', '{{ $vo['name'] }}')" title="设置角色权限">
											<i class="icon-plus">
											</i>
										</a>
										&nbsp;
										<a href="javascript:;" onclick="editRole('{{ $key }}')" title="修改角色内容">
											<i class="icon-edit">
											</i>
										</a>
										&nbsp;
										<a href="{{ url('user/role/delete?id='.$key) }}" onclick="if(confirm('{{"确定删除角色".$vo['name']."?"}}') == false) return false;" title="删除角色">
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

	<div class="modal fade" id="addRoleModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						增加用户角色
					</h4>
				</div>
				<form class="form-horizontal row-border" action="{{ url('user/roles/add') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								角色名称
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="name" required autofocus>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">
								角色描述
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="desc">
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

	<div class="modal fade" id="editRoleModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						修改用户角色
					</h4>
				</div>
				<form class="form-horizontal row-border" action="{{ url('user/role/update') }}" method="post">
					{{ csrf_field() }}
					<input id="editRoleModal_id" type="hidden" class="form-control" name="id">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								角色名称
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input id="editRoleModal_name" type="text" class="form-control" name="name" required autofocus>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">
								角色描述
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input id="editRoleModal_desc" type="text" class="form-control" name="desc">
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

	<div class="modal fade" id="setRolePermissionsModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 id="setRolePermissionsModal_title" class="modal-title">
					</h4>
				</div>

				<form class="form-horizontal row-border" action="{{ url('user/role/setRolePermissions') }}" method="post">
					{{ csrf_field() }}

					<div class="modal-body">
						<div class="form-group">
							<div class="col-md-3">
							</div>
							<div class="col-md-8" id="setRolePermissionsModal_permissions">
							</div>
						</div>
					</div>

					<input id="setRolePermissionsModal_role_id" type="hidden" class="form-control" name="id">

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
		function editRole(id)
		{
			$('#editRoleModal_id').val(id);

			$.getJSON('{{ url('user/role') }}', {id: id}, function(data){
				$('#editRoleModal_name').val(data.name);
				$('#editRoleModal_desc').val(data.description);
			});

			$('#editRoleModal').modal();
		}

		function setRolePermissions(id, name)
		{
			$('#setRolePermissionsModal_title').html('设置'+name+'的权限');
			$('#setRolePermissionsModal_permissions').html('');
			$('#setRolePermissionsModal_role_id').val(id);

			$.getJSON('{{ url('user/role/getRolePermissions') }}', {id: id}, function(data){
				var s = '';
				for(var i=0; i<data.length; i++)
				{
					s += '<label class="checkbox"><input type="checkbox" class="uniform" value="' + data[i].id +'" name="permissions[]"' + (data[i].checked ? ' checked' : '') + '>' + data[i].name + '</label>';
				}
				$('#setRolePermissionsModal_permissions').html(s);
			});

			$('#setRolePermissionsModal').modal();
		}
	</script>
@stop
