@extends('common.layouts')

@section('menu')
	场所管理
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
						场所管理
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
							<a data-toggle="modal" href="#addSiteManage">
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
									名称
								</th>
								<th class="col-md-2">
									关键词
								</th>
								<th class="col-md-1">
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($site_data as $key => $vo)
								<tr>
									<td class="col-md-2">
										{{ $key }}
									</td>
									<td>
										@foreach($vo as $keyword)
											{{ $keyword->keyword }}
										@endforeach
									</td>
									<td>
										<a href="javascript:;" onclick="editSiteManage('{{ $key }}')" class="bs-tooltip" title="修改">
											<i class="icon-edit">
											</i>
										</a>
										&nbsp;
										<a href="{{ url('site/deleteSiteManage', ['name' => $key]) }}" class="bs-tooltip" title="删除用户" onclick="if(confirm('确定删除') == false) return false;">
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

	<div class="modal fade" id="addSiteManage">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						新增场所管理
					</h4>
				</div>

				<form class="form-horizontal row-border" action="" method="post">

					{{ csrf_field() }}

					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								名称
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="name" required autofocus>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">
								关键词
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="keyword" required>
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

	<div class="modal fade" id="editSiteManage">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						新增场所管理
					</h4>
				</div>

				<form class="form-horizontal row-border" action="{{ url('site/editSiteManage') }}" method="post">

					{{ csrf_field() }}
					
					<input type="hidden" name="id" value="" id="id">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-md-3 control-label">
								名称
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="name" id="name" required autofocus>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">
								关键词
							</label>
							<div class="col-md-8" style="margin-top: 6px;">
								<input type="text" class="form-control" name="keyword" id="keyword" required>
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
		function editSiteManage(name){
			$.getJSON('{{ url('site/editSiteManage') }}', {name: name}, function(data){
				$('#name').val(name);
				$('#keyword').val(data.join());
			});
			
			$('#editSiteManage').modal();
		}
	</script>
@stop
