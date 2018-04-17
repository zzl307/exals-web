
		function showSiteDetail(site_id)
		{
			$.getJSON('{{ url('site/getSiteInfo') }}', {site_id: site_id}, function(data)
			{
				if (data)
				{	
					showSiteDetailModal(data);
					$('#showSiteDetailModal').on('hidden.bs.modal', function () {
						$('#site_id_'+site_id).css('color', '#908f90');
					});
				}
			});
		}
<div class="modal fade" id="editClientModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					终端实名修改
				</h4>
			</div>
			<form class="form-horizontal" action="{{ url('client/update', ['client_mac' => $vo->mac]) }}" method="post">
				{{ csrf_field() }}

				<div class="modal-body">
					<div class="form-group">
						<label for="mac" class="col-md-3 control-label">
							终端MAC
						</label>
						<div class="col-md-8" style="margin-top: 6px;">
							<input id="client_mac" type="text" class="form-control" name="client_mac" value="{{ $vo->mac }}" required readonly>
						</div>
					</div>

					<div class="form-group">
						<label for="user_id" class="col-md-3 control-label">
							用户实名
						</label>
						<div class="col-md-8" style="margin-top: 6px;">
							<input id="user_id" type="text" class="form-control" name="user_id" value="{{ $vo->user_id }}" required autofocus>
						</div>
					</div>

					<div class="form-group">
						<label for="id_type" class="col-md-3 control-label">
							实名类型
						</label>
						<div class="col-md-8" style="margin-top: 6px;">
							<input id="id_type" type="text" class="form-control" name="id_type" value="{{ $vo->id_type }}" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-8 col-md-offset-3">
						<button type="submit" class="btn btn-info">
							确认修改
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
