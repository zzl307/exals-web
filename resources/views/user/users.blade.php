@extends('common.layouts')

@section('menu')
	用户列表
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
						用户列表
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
							<a data-toggle="modal" href="#addUserModal">
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
									用户名
								</th>
								<th class="col-md-2">
									邮箱
								</th>
								<th class="col-md-3">
									角色
								</th>
								<th class="col-md-1">
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $id => $user)
								<tr>
									<td class="col-md-2">
										{{ $user['name'] }}
									</td>
									<td>
										{{ $user['email'] }}
									</td>
									<td>
										{{ $user['roles'] }}
									</td>
									<td>
										<a href="javascript:;" onclick="setRoles('{{ $id }}', '{{ $user['name'] }}')" title="配置角色">
											<i class="icon-user">
											</i>
										</a>
										&nbsp;
										<a href="{{ url('user/resetPassword?id='.$id) }}" onclick="if(confirm('{{"确定重置".$user['name']."的密码?"}}') == false) return false;" title="重置">
											<i class="icon-key">
											</i>
										</a>
										&nbsp;
										<a href="{{ url('user/delete?id='.$id) }}" onclick="if(confirm('{{"确定删除用户".$user['name']."?"}}') == false) return false;" title="删除用户">
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

	<div class="modal fade" id="addUserModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						新增用户
					</h4>
				</div>

				<form class="form-horizontal row-border" action="{{ url('user/add') }}" method="post">
					{{ csrf_field() }}

					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								用户名
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="name" required autofocus>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">
								邮箱
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="email" required>
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

	<div class="modal fade" id="setRolesModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 id="setRolesModal_title" class="modal-title">
					</h4>
				</div>

				<form class="form-horizontal row-border" action="{{ url('user/setUserRoles') }}" method="post">
					{{ csrf_field() }}

					<div class="modal-body">
						<div class="form-group">
							<div class="col-md-3">
							</div>
							<div class="col-md-8" id="setRolesModal_roles">
							</div>
						</div>
					</div>

					<input id="setRolesModal_user_id" type="hidden" class="form-control" name="id">

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
		function setRoles(id, name)
		{
			$('#setRolesModal_title').html('设置'+name+'的角色');
			$('#setRolesModal_roles').html('');
			$('#setRolesModal_user_id').val(id);

			$.getJSON('{{ url('user/getUserRoles') }}', {id: id}, function(data){
				var s = '';
				for(var i=0; i<data.length; i++)
				{
					s += '<label class="checkbox"><input type="checkbox" class="uniform" value="' + data[i].id +'" name="roles[]"' + (data[i].checked ? ' checked' : '') + '>' + data[i].name + '</label>';
				}
				$('#setRolesModal_roles').html(s);
			});

			$('#setRolesModal').modal();
		}
	</script>
@stop
