@extends('common.layouts')

@section('menu')
	实名记录
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
						实名记录比对
					</h4>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="{{ url('match/do') }}" method="post">
						{{ csrf_field() }}

                        <div class="modal-body">
							<div class="form-group">
								<label for="period" class="col-md-3 control-label">
									时差幅度
								</label>
								<div class="col-md-6">
									<input id="range" class="form-control" name="range" type="text" value="10" placeholder="分钟" required>
									<p class="form-control-static text-danger">{{$errors->first('site_id')}}</p>
								</div>
							</div>

							<div class="form-group">
								<label for="site_id" class="col-md-3 control-label">
									场所号
								</label>
								<div class="col-md-6">
									<input id="site_id" class="form-control" name="site_id" type="text" placeholder="场所号" value="{{ isset($site_id) ? $site_id : ''}}"required>
									<p class="form-control-static text-danger">{{$errors->first('site_id')}}</p>
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-md-3 control-label">
									实名记录
								</label>
								<div class="col-md-6">
									<textarea rows="15" cols="5" name="content" class="form-control" placeholder="时间, MAC, 实名">{{ isset($content) ? $content : '' }}</textarea>
									<p class="form-control-static text-danger">{{$errors->first('content')}}</p>
								</div>
							</div>
						</div>
						<div class="modal-footer">
			                <button type="submit" class="btn btn-primary">
			                    比对
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
