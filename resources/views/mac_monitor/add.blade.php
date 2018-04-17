@extends('common.layouts')

@section('menu')
	添加监控终端
@stop

@section('content')

	<div class="row row-bg">
		<div class="col-md-12">

			@include('common.message')

			<div class="widget box">
				<div class="widget-header">
					<h4>
						<i class="icon-reorder">
						</i>
						添加监控终端
					</h4>
				</div>
				<div class="widget-content">
					<form class="form-horizontal row-border" action="" method="post">

						{{ csrf_field() }}
						<div class="modal-body">
							<div class="form-group">
								<label for="mac" class="col-md-3 control-label">
									终端MAC
								</label>
								<div class="col-md-6" style="margin-top: 6px;">
									<input id="mac" class="form-control" name="Monitor[mac]" value="{{ isset($data) ? $data['mac'] : '' }}" type="text" required>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="javascript:history.back(-1)">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									返回
								</button>
							</a>
							<button type="submit" class="btn btn-primary">
								确认添加
							</button>
						</div>
					</form>
				</div>
			</div>
			@if(isset($errmsg))
				<div class="alert alert-danger fade in">
				<i class="icon-remove close" data-dismiss="alert">
				</i>
				<strong>
					{{ $errmsg }}
				</strong>
			</div>
			@endif
		</div>
	</div>

@stop
