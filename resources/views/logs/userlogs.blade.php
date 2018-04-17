@extends('common.layouts')

@section('menu')
	用户数据查询
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
						用户数据查询
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
                                <i class="icon-refresh">
                                </i>
                                刷新
                            </span>
                            <a href="javascript:;" onclick="idTypeCount(1)" style="text-decoration: none;">
								<span class="btn btn-xs btn-warning bs-tooltip" title="点击查询虚拟身份统计" style="border-left: 0px;">
									<i class="icon-search">
									</i>
									虚拟身份统计
								</span>
							</a>
							<a href="javascript:;" onclick="idTypeCount(0)" style="text-decoration: none;">
								<span class="btn btn-xs btn-info bs-tooltip" title="点击查询实名身份统计" style="border-left: 0px;float: right;">
									<i class="icon-search">
									</i>
									实名身份统计
								</span>
							</a>
						</div>
					</div>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="" method="get">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-2">
								<input type="text" class="form-control datepicker" name="date" id="date" value="{{ isset($data) ? $data['date'] : date('Y-m-d', time()) }}" placeholder="日期">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="site_id" class="form-control" name="site_id" value="{{ isset($data) ? $data['site_id'] : '' }}" type="text" placeholder="场所号">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="mac" class="form-control" name="mac" value="{{ isset($data) ? $data['mac'] : '' }}" type="text" placeholder="终端MAC">
							</div>
   							<div class="col-md-2" style="margin-left: -15px;">
								<input id="user_id" class="form-control" name="user_id" value="{{ isset($data) ? $data['user_id'] : '' }}" type="text" placeholder="用户身份">
							</div>
							<div class="col-md-2" style="margin-left: -15px;">
								<input id="id_type" class="form-control" name="id_type" value="{{ isset($data) ? $data['id_type'] : '' }}" type="text" placeholder="身份类型">
							</div>
							<div class="col-md-1" style="margin-left: -15px;">
								<select id="action" class="form-control" name="vid">
									<option value="-1" selected>全部</option>
									<option value="0" {{ isset($data) && $data['vid'] == 0 ? 'selected' : '' }}>实名</option>
									<option value="1" {{ isset($data) && $data['vid'] == 1 ? 'selected' : '' }}>虚拟</option>
								</select>
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
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th>
										时间
									</th>
									<th>
										场所号
									</th>
									<th>
										终端MAC
									</th>
									<th>
										终端IP
									</th>
									<th>
										事件
									</th>
									<th>
										用户身份
									</th>
									<th>
										身份类型
									</th>
									<th>
										获取方法
									</th>
									<th colspan="2">
										端口范围
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($logs as $vo)
									<tr>
										<td>
											{{ $vo->time }}
										</td>
										<td>
											{{ $vo->site_id }}
										</td>
										<td>
											{{ $vo->mac }}
										</td>
										<td>
											{{ $vo->local_ip }}
										</td>
										<td>
											{{ $vo->event }}
										</td>
										<td>
											{{ $vo->user_id }}
										</td>
										<td>
											{{ $vo->id_type }}
											@if($vo->vid == 0)
												<span class="label label-info pull-right">
													实名
												</span>
											@else
												<span class="label label-warning pull-right">
													虚拟
												</span>
											@endif
										</td>
										<td>
											@if ($vo->method == 1)
												反查
											@elseif ($vo->method == 2)
												消息
											@elseif ($vo->method == 3)
												Portal截取
											@elseif ($vo->method == 4)
												Radius认证
											@elseif ($vo->method == 5)
												第三方
											@elseif ($vo->method == 6)
												中心反查
											@elseif ($vo->method == 7)
												Radius计费
											@elseif ($vo->method == 8)
												缓存
											@else
												未知
											@endif
										</td>
										<td>
											{{ $vo->start_port }}
										</td>
										<td>
											{{ $vo->end_port }}
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
														'user_id' => isset($data['user_id']) ? $data['user_id'] : '',
														'date' => isset($data['date']) ? $data['date'] : '',
														'id_type' => isset($data['id_type']) ? $data['id_type'] : '',
														'vid' => isset($data['vid']) ? $data['vid'] : ''
													 ])->links()}}
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

	<div class="modal fade" id="idTypeCountModal">
		<div class="modal-dialog" style="width: 1270px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						虚拟身份统计
					</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-highlight-head">
						<thead id="thead">
							
						</thead>
						<tbody id="tbody">
							
						</tbody>
					</table>
					<div class="modal-footer table-bordered">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="closeButton">
							关闭
						</button>
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
		$(document).ready(function(){
			$(".datepicker").datepicker({
				inline: true,
				defaultDate: +7,
				showOtherMonths: true,
				autoSize: true,
				dateFormat: "yy-mm-dd"
			});
		});

		function idTypeCount(vid){
			var date = $('#date').val();
			var a = $('.widget');
			$.ajax({
				url: '{{ url('logs/idTypeCount') }}',
				data: {date: date, vid:vid},
				dataType:'json',
				beforeSend:function(){
                	$('.widget').block({
                		message: null,
		                css: {
		                    top: "10%",
		                    border: "none",
		                    padding: "2px",
		                    backgroundColor: "none"
		                },
		                overlayCSS: {
		                    backgroundColor: "#000",
		                    opacity: 0.05,
		                    cursor: "wait"
		                }
                	});
                },
                success:function(data){
                    App.unblockUI(a);
                    if(data == '没有数据'){
						alert('没有数据');
					}else{
						$('#thead').html(data.thead);
						$('#tbody').html(data.tbody);
						$('#idTypeCountModal').modal();
					}
                }
			});
		}
	</script>
@stop
