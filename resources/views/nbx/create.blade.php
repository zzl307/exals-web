@extends('common.layouts')

@section('menu')
	诺必行数据配置
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
						诺必行数据配置
					</h4>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="{{ route('nbx.store') }}" method="post">

						{{ csrf_field() }}
						
						<input type="hidden" name="id" value="{{ isset($data['id']) ? $data['id'] : '' }}">
                        <div class="modal-body">
							<div class="form-group">
								<label for="period" class="col-md-3 control-label">
									服务器地址
								</label>
								<div class="col-md-6">
									<input id="server" class="form-control" name="server" type="text" value="{{ isset($data['server']) ? $data['server'] : '' }}" placeholder="服务器地址" required>
								</div>
							</div>

							<div class="form-group">
								<label for="site_id" class="col-md-3 control-label">
									端口
								</label>
								<div class="col-md-6">
									<input id="port" class="form-control" name="port" type="text" placeholder="端口" value="{{ isset($data['port']) ? $data['port'] : ''}}"required>
								</div>
							</div>
							
							@if (!empty($data['domains']))
								<div class="form-group">
									<label for="" class="col-md-3 control-label">
										域名地址
									</label>
									<div class="col-md-6">
										<input type="text" id="tags3" class="tags-autocomplete" name="domains" value="{{ $data['domains'] }}">
									</div>
								</div>
							@else
								<div class="form-group">
									<label for="" class="col-md-3 control-label">
										域名地址
									</label>
									<div class="col-md-6">
										<textarea rows="15" cols="5" name="domains" class="form-control" placeholder="域名地址" required>{{ isset($data['domains']) ? $data['domains'] : '' }}</textarea>
									</div>
								</div>
							@endif
							<div class="form-group">
								<label for="period" class="col-md-3 control-label">
									上传数据到lsdata
								</label>
								<div class="col-md-6">
									<label class="radio-inline">
										<input type="radio" name="upload" value="1" @if (isset($data['upload']) && $data['upload'] == 1) checked @endif>
										是
									</label>
									<label class="radio-inline">
										<input type="radio" name="upload" value="0" @if (isset($data['upload']) && $data['upload'] == 0) checked @endif>
										否
									</label>
								</div>
							</div>
						</div>
						<div class="modal-footer no-padding">
			                <button type="submit" class="btn btn-primary">
			                    配置
			                </button>
			            </div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop
