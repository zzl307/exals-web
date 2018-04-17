@extends('common.layouts')

@section('menu')
	终端频次查询
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
						终端频次查询
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
                                <i class="icon-refresh">
                                </i>
                            </span>
						</div>
					</div>
				</div>
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="" method="get">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-2">
								<input type="text" class="form-control datepicker" name="date" value="{{ isset($data) ? $data['date'] : date('Y-m-d', time()) }}" placeholder="日期" required>
							</div>
							<div class="col-md-1" style="margin-left: -15px;">
								<input type="text" class="form-control" name="action" id="spinner-default" value="{{ isset($data) ? $data['action'] : 1 }}" placeholder="出现频次" required>
							</div>
							<div class="col-md-1">
								<button class="btn btn-sm" style="padding: 5px 16px;margin-left: -15px;">搜索</button>
							</div>
						</div>
					</form>
				</div>

				@if(isset($errmsg))
					<div class="alert alert-danger fade in">
						<i class="icon-remove close" data-dismiss="alert">
						</i>
						<strong>
							错误: 
						</strong>
						{{ $errmsg }}
					</div>
				@endif

				@if (!empty($screenshotData))
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th>
										终端MAC
									</th>
									<th>
										终端频次
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($screenshotData as $vo)
									<tr>
										<td class="col-md-4">
											<a href="javascript:;" onclick="getMacSite('{{ $vo['mac'] }}', '{{ $data['date'] }}');" class="bs-tooltip" title="查看所在场所">
												{{ $vo['mac'] }}
											</a>
											
										</td>
										<td>
											{{ $vo['count'] }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="row">
						    <div class="table-footer">
						        <div class="col-md-12">
						            {{$paginator->appends([
														'date' => isset($data['date']) ? $data['date'] : '',
														'action' => isset($data['action']) ? $data['action'] : ''
													 ])->links()}}
						        </div>
						    </div>
						</div>
					</div>
				@endif

			</div>
		</div>
	</div>

	<div class="modal fade" id="selectMac">
		<div class="modal-dialog" style="width: 1270px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						查看所在场所
					</h4>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th>
										时间
									</th>
									<th>
										动作
									</th>
									<th>
										审计设备
									</th>
									<th>
										场所号
									</th>
									<th>
										场所名称
									</th>
									<th>
										终端MAC
									</th>
									<th>
										终端IP
									</th>
									<th>
										用户身份
										</th>
									<th>
										身份类型
									</th>
									<th>
										获取方式
									</th>
								</tr>
							</thead>
						<tbody id="getMacSite">
							
						</tbody>
					</table>
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

        function getMacSite(mac, date){
        	$.getJSON('{{ url('client/macSite') }}', {mac: mac, date: date}, function(data){
        		if(data){
        			$('#getMacSite').html(data);
					$('#selectMac').modal();
        		}
        		if(data == ''){
        			alert('没有找到相关场所信息');
        		}
        	});
        }
	</script>
@stop
