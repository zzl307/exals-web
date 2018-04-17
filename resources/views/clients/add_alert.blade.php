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
						敏感人员管理
					</h4>
				</div>
				<div class="widget-content">
					<form class="form-horizontal" action="{{ route('addAlert') }}" method="post">
						{{ csrf_field() }}

						<div>
							<input id="origurl" type="hidden" class="form-control" name="origurl" value="{{ $origurl }}">

							@if (old('update'))
								<input id="update" type="hidden" class="form-control" name="update" value="1">
							@endif

							<div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
								<label class="col-md-2 control-label">
									敏感用户
								</label>
								<div class="col-md-4">
									<input type="text" class="form-control" name="user" value="{{ old('user') ?: request()->input('user') }}" required {{ old('update') ? 'readonly' : '' }}>
								</div>
								@if ($errors->has('user'))
									<span class="help-block">
										<strong>{{ $errors->first('user') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
								<label class="col-md-2 control-label">
									原因
								</label>
								<div class="col-md-4">
									<textarea rows="3" cols="5" class="form-control"  name="reason" required>{{ old('reason') }}</textarea>
								</div>
								@if ($errors->has('reason'))
									<span class="help-block">
										<strong>{{ $errors->first('reason') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('expiry') ? ' has-error' : '' }}">
								<label class="col-md-2 control-label">
									到期时间
								</label>
								<div class="col-md-4">
									<input type="text" class="form-control datepicker" name="expiry" value="{{ old('expiry') }}">
								</div>
								@if ($errors->has('expiry'))
									<span class="help-block">
										<strong>{{ $errors->first('expiry') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<div class="checkbox">
										<input type="checkbox" name="data" {{ old('data') ? 'checked' : '' }}>监控数据
									</div>
								</div>
							</div>

							<hr>

							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<button class="btn btn-sm btn-info" style="padding: 5px 36px; z-index: 0;">
										确定
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="text/javascript" src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".datepicker").datepicker({
				inline: true,
				defaultDate: +7,
				showOtherMonths: true,
				autoSize: true,
				dateFormat: "yy-mm-dd"
			});
		});
	</script>
@stop
