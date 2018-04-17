@extends('common.layouts')

@section('menu')
	实名信息添加
@stop

@section('content')

	<div class="row">
		<div class="col-md-12">

			<div class="widget box">
				<div class="widget-header">
					<h4>
						<i class="icon-reorder">
						</i>
						实名信息管理
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<a href="{{ url($origurl) }}">
								<span class="btn btn-xs">
									<i class="icon-reply">
									</i>
									返回
								</span>
							</a>
						</div>
					</div>
				</div>
				<div class="widget-content">
					<form class="form-horizontal" action="{{ route('addClient') }}" method="post">
						{{ csrf_field() }}

						<div>
							<input id="origurl" type="hidden" class="form-control" name="origurl" value="{{ $origurl }}">

							@if (old('update'))
								<input id="update" type="hidden" class="form-control" name="update" value="1">
							@endif

							<div class="form-group{{ $errors->has('client_mac') ? ' has-error' : '' }}">
								<label for="client_mac" class="col-md-2 control-label">
									终端MAC
								</label>
								<div class="col-md-4">
									<input id="client_mac" type="text" class="form-control" name="client_mac" value="{{ old('client_mac') }}" required {{ old('update') ? 'readonly' : '' }}>
								</div>
								@if ($errors->has('client_mac'))
									<span class="help-block">
										<strong>{{ $errors->first('client_mac') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
								<label for="user_id" class="col-md-2 control-label">
									用户实名
								</label>
								<div class="col-md-4">
									<input id="user_id" type="text" class="form-control" name="user_id" value="{{ old('user_id') }}" required>
								</div>
								@if ($errors->has('user_id'))
									<span class="help-block">
										<strong>{{ $errors->first('user_id') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('id_type') ? ' has-error' : '' }}">
								<label for="id_type" class="col-md-2 control-label">
									实名类型
								</label>
								<div class="col-md-4">
									<input id="id_type" type="text" class="form-control" name="id_type" value="{{ old('id_type') }}" required>
								</div>
								@if ($errors->has('id_type'))
									<span class="help-block">
										<strong>{{ $errors->first('id_type') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<div class="col-md-6 checkbox">
										<input type="checkbox" name="persist" {{ old('persist') ? 'checked' : '' }}>免除清理
									</div>
									<div class="col-md-6 checkbox">
										<input type="checkbox" name="alert" {{ old('alert') ? 'checked' : '' }}>敏感人员
									</div>
								</div>
							</div>

							<hr>

							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<button class="btn btn-sm btn-info" type="submit" style="padding: 5px 36px;">
										确定
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop
