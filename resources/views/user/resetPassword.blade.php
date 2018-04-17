<div class="modal fade" id="resetPasswordModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					新密码
				</h4>
			</div>

			<form class="form-horizontal row-border" action="{{ url('/resetPassword') }}" method="post">

				{{ csrf_field() }}
				
				<div class="modal-body">
					<div class="form-group">
						<div class="col-md-12" style="margin-top: 6px;">
							<input type="password" class="form-control" name="password" required autofocus>
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
