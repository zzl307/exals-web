@extends('common.layouts')

@section('menu')
	上网数据查询
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
						上网数据查询
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
							<a data-toggle="modal" href="#advancedSearchModal" style="text-decoration: none;">
								<span class="btn btn-xs btn-info" style="border-left: 0px;">
									<i class="icon-search">
									</i>
									高级搜索
								</span>
							</a>
							@if(isset($data))
								@if(isset($logs) && count($logs) > 0)
									<a href="{{ url('logs/export?site_id='.$data['site_id'].'&mac='.$data['mac'].'&date='.$data['date'].'&type='.$data['type'].'&field='.$data['field'].'&keyword='.$data['keyword']) }}" style="float: right;">
										<span class="btn btn-xs btn-success" style="border-left: 0px;">
											<i class="icon-file-text-alt">
											</i>
											导出
										</span>
									</a>
								@endif
							@endif
						</div>
					</div>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="" method="get">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-2">
								<input class="form-control datepicker" name="date" value="{{ isset($data) ? $data['date'] : date('Y-m-d', time()) }}" type="text" required placeholder="日期">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input class="form-control" name="site_id" value="{{ isset($data) ? $data['site_id'] : '' }}" type="text" placeholder="场所号">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input class="form-control" name="mac" value="{{ isset($data) ? $data['mac'] : '' }}" type="text" placeholder="终端MAC">
							</div>
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
					</form>
				</div>

				@if(isset($errmsg))
					<div class="alert alert-danger fade in">
					<i class="icon-remove close" data-dismiss="alert"></i>
					<strong>{{ $errmsg }}</strong>
				</div>
				@endif
				
				@if(!empty($logs))
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head" style="table-layout: fixed; width: 100%">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th class="col-xs-1">
										时间
									</th>
									<th style="width: 10%;">
										场所号
									</th>
									<th style="width: 10%;">
										终端MAC
									</th>
									<th style="width: 15%;">
										连接记录
									</th>
									<th style="width: 5%;">
										类型
									</th>
									<th class="col-xs-5">
										内容
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($logs as $vo)
									<tr>
										<td>
											{{ $vo->time }}
										</td>
										<td style="word-wrap: break-word">
											{{ $vo->site_id }}
										</td>
										<td style="word-wrap: break-word">
											{{ $vo->mac }}
										</td>
										<td>
											<div>
												<strong style="font-size: 12px;">src</strong>: {{ $vo->local_ip }}:{{ $vo->local_port }}
											</div>
											<div>
												<strong style="font-size: 12px;">dst</strong>: {{ $vo->remote_ip }}:{{ $vo->remote_port }}
											</div>
										</td>
										<td>
											{{ $vo->type }}
										</td>
										<td>
											<?php $fields = json_decode($vo->url, true);?>
											@if($fields != null)
												@foreach($fields as $key => $val)
													<div style="word-wrap: break-word"><strong>{{ $key.': ' }}</strong>{{ substr($val, 0, 64) }}</div>
												@endforeach
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="row">
							<div class="table-footer">
								<div class="col-md-6">
								</div>
								<div class="col-md-6">
									{{$logs->appends([
														'site_id' => isset($data['site_id']) ? $data['site_id'] : '',
														'mac' => isset($data['mac']) ? $data['mac'] : '',
														'date' => isset($data['date']) ? $data['date'] : '',
														'type' => isset($data['type']) ? $data['type'] : '',
														'field' => isset($data['field']) ? $data['field'] : '',
														'keyword' => isset($data['keyword']) ? $data['keyword'] : ''
													 ])->links()}}
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

	<div class="modal fade" id="advancedSearchModal">
		<div class="modal-dialog" style="width: 1000px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						高级搜索
					</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal row-border" action="" method="get">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<tbody>
								<tr>
									<td class="col-md-3">
										<strong>
											日期
										</strong>
									</td>
									<td>
										<input class="form-control datepicker" name="date" value="{{ isset($data) ? $data['date'] : date('Y-m-d', time()) }}" type="text" required>
									</td>
								</tr>
								<tr>
									<td>
										<strong>
											场所号
										</strong>
									</td>
									<td>
										<input class="form-control" name="site_id" value="{{ isset($data) ? $data['site_id'] : '' }}" type="text">
									</td>
								</tr>
								<tr>
									<td>
										<strong>
											终端MAC
										</strong>
									</td>
									<td>
										<input class="form-control" name="mac" value="{{ isset($data) ? $data['mac'] : '' }}" type="text">
									</td>
								</tr>
								<tr>
									<td>
										<strong>
											数据类型
										</strong>
									</td>
									<td>
										<div class='col-md-6' style='margin-left: -15px;'>
											<select id="advancedSearchModal_type" class='form-control col-md-12 full-width-fix' name='type' style='width: 10%;' required>
												<option value=''>请选择数据类型</option>
												<option value="http" {{ isset($data) && $data['type'] == 'http' ? 'selected' : '' }}>HTTP</option>
											</select>
										</div>
										<div class='col-md-6' style='margin-left: -15px;'>
											<select id="advancedSearchModal_field" class='form-control col-md-12 full-width-fix' name='field' style='width: 10%;' required>
												<option value=''>请选择数据字段</option>
												<option value="url" {{ isset($data) && $data['field'] == 'url' ? 'selected' : '' }}>URL</option>
												<option value="user-agent" {{ isset($data) && $data['field'] == 'user-agent' ? 'selected' : '' }}>User-Agent</option>
												<option value="referer" {{ isset($data) && $data['field'] == 'referer' ? 'selected' : '' }}>Referer</option>
												<option value="cookie" {{ isset($data) && $data['field'] == 'cookie' ? 'selected' : '' }}>Cookie</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<strong>
											关键字
										</strong>
									</td>
									<td>
										<input class="form-control" name="keyword" value="{{ old('keyword') ? old('keyword') : isset($data) ? $data['keyword'] : '' }}" type="text">
									</td>
								</tr>
							</tbody>
						</table>
						<div class="modal-footer table-bordered">
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
	</div>

@stop

@section('javascript')
	<script type="text/javascript" src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
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
