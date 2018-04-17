@extends('common.layouts')

@section('menu')
	场所统计
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
						场所统计
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
							@can('site_advanced_search')
								<a data-toggle="modal" href="#statsAdvancedSearchModal" style="text-decoration: none;float: right;">
									<span class="btn btn-xs btn-info">
										<i class="icon-search">
										</i>
										高级搜索
									</span>
								</a>
							@endcan
							@if(isset($query))
								<a href="{{ url('/') }}/site/siteExcel?{{ $query }}"
									<span class="btn btn-xs btn-success">
										<i class="icon-file-text-alt">
										</i>
										导出
									</span>
								</a>
							@endif
						</div>
					</div>
				</div>
				
				<div class="widget-content no-padding">
					<form class="form-horizontal row-border" action="{{ url('site/stats/search') }}" method="GET">
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-3">
								<input type="text" name="site_id" placeholder="场所号" class="form-control" required>
							</div>
							<button class="btn btn-sm btn-info" style="padding: 5px 16px;">显示统计数据</button>
						</div>
					</form>
				</div>

				@if(isset($stats))
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head" style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
							<thead>
								<tr>
									<th rowspan="2" style="vertical-align: middle; text-align:center;">
										日期
									</th>
									<th rowspan="2" style="vertical-align: middle; text-align:center;">
										场所号
									</th>
									<th rowspan="2" style="vertical-align: middle; text-align:center;">
										场所名称
									</th>
									<th rowspan="2" style="vertical-align: middle; text-align:center;">
										上网终端	
									</th>
									<th colspan="8" style="vertical-align: middle; text-align:center;">
										实名分类
									</th>
									<th colspan="6" style="vertical-align: middle; text-align:center;">	
										审计数据量
									</th>
								</tr>
								<tr>
									<th style="vertical-align: middle; text-align:center;">
										反查
									</th>
									<th style="vertical-align: middle; text-align:center;">
										UDP消息
									</th>
									<th style="vertical-align: middle; text-align:center;">
										Portal
									</th>
									<th style="vertical-align: middle; text-align:center;">
										Radius认证
									</th>
									<th style="vertical-align: middle; text-align:center;">
										Radius计费
									</th>
									<th style="vertical-align: middle; text-align:center;">
										第三方
									</th>
									<th style="vertical-align: middle; text-align:center;">
										中心反查
									</th>
									<th style="vertical-align: middle; text-align:center;">
										缓存
									</th>
									<th style="vertical-align: middle; text-align:center;">
										实名登录
									</th>
									<th style="vertical-align: middle; text-align:center;">
										实名登出
									</th>
									<th style="vertical-align: middle; text-align:center;">
										虚拟身份
									</th>
									<th style="vertical-align: middle; text-align:center;">
										上网数据
									</th>
									<th style="vertical-align: middle; text-align:center;">
										上网日志
									</th>
									<th style="vertical-align: middle; text-align:center;">
										无线感知
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($stats as $vo)
									<tr>
										<td rowspan="2">
											{{ $vo->date }}
										</td>
										<td rowspan="2">
										<a href="{{ url('site/stats/search?site_id=').$vo->site_id }}">
												{{ $vo->site_id }}
											</a>
										</td>
										<td rowspan="2">
											{{ $vo->site_name }}
										</td>
										<td rowspan="2">
											{{ $vo->clients }}
										</td>
										<td rowspan="2">
											{{ $vo->login_query_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_udp_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_portal_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_radius_auth_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_radius_acct_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_vendor_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_center_rcvd }}
										</td>
										<td rowspan="2">
											{{ $vo->login_cached_rcvd }}
										</td>
										<td>
											{{ $vo->login_rcvd }}
										</td>
										<td>
											{{ $vo->logout_rcvd }}
										</td>
										<td>
											{{ $vo->vid_rcvd }}
										</td>
										<td>
											{{ $vo->data_rcvd }}
										</td>
										<td>
											{{ $vo->conn_rcvd }}
										</td>
										<td>
											{{ $vo->wls_rcvd }}
										</td>
									</tr>
									<tr>
										<td>
											{{ $vo->login_sent }}
										</td>
										<td>
											{{ $vo->logout_sent }}
										</td>
										<td>
											{{ $vo->vid_sent }}
										</td>
										<td>
											{{ $vo->data_sent }}
										</td>
										<td>
											{{ $vo->conn_sent }}
										</td>
										<td>
											{{ $vo->wls_sent }}
										</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr class="table-footer">
									<td colspan="3" style="vertical-align: middle;">
										<strong>总计：{{ $total }}</strong>
									</td>
									<td colspan="20">
										<div>
											@if(isset($paginator))
												{{ $paginator->links() }}
											@endif
										</div>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>

					<div class="modal-footer">
						<a href="javascript:history.back(-1)">
							<button type="button" class="btn btn-default" data-dismiss="modal">
								返回
							</button>
						</a>
					</div>
				@endif
			</div>
		</div>
	</div>

@can('site_advanced_search')
	<div class="modal fade" id="statsAdvancedSearchModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="line-height: 0px;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title">
						场所统计高级搜索
					</h4>
				</div>
				<form class="form-horizontal row-border" action="{{ url('site/stats/search') }}" method="get">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="widget">
									<div class="widget-content">
										<div class="panel-group" id="accordion">
											<div class="panel panel-default">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#statsAdvancedSearchModal_tab1" style="text-decoration: none;">
														<h3 class="panel-title">
															基本条件
														</h3>
													</a>
												</div>
												<div id="statsAdvancedSearchModal_tab1" class="panel-collapse collapse in">
													<div class="panel-body">
														<div class="form-group">
															<label class="col-md-3 control-label">
																日期
															</label>
															<div class="col-md-6">
																<input type="text" class="form-control datepicker" name="date" value="{{ date('Y-m-d') }}" required>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																场所号
															</label>
															<div class="col-md-6">
																<input type="text" name="site_id" class="form-control">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																场所名称
															</label>
															<div class="col-md-6">
																<input type="text" name="site_name" class="form-control" style="height: 30px;">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																上网终端
															</label>
															<div class="col-md-2">
																<input type="text" name="clients_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="clients_max" class="form-control input-width-mini">
															</div>
														</div>
													</div>
												</div>
											</div>
	
											<div class="panel panel-default">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#statsAdvancedSearchMoal_tab2" style="text-decoration: none;">
														<h3 class="panel-title">
															实名类型
														</h3>
													</a>
												</div>
												<div id="statsAdvancedSearchMoal_tab2" class="panel-collapse collapse">
													<div class="panel-body">
														<div class="form-group">
															<label class="col-md-3 control-label">
																反查
															</label>
															<div class="col-md-2">
																<input type="text" name="login_query_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_query_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																UDP消息
															</label>
															<div class="col-md-2">
																<input type="text" name="login_udp_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_udp_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																Portal获取
															</label>
															<div class="col-md-2">
																<input type="text" name="login_portal_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_portal_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																Radius认证
															</label>
															<div class="col-md-2">
																<input type="text" name="login_radius_auth_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_radius_auth_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																Radius计费
															</label>
															<div class="col-md-2">
																<input type="text" name="login_radius_acct_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_radius_acct_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																第三方
															</label>
															<div class="col-md-2">
																<input type="text" name="login_vendor_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_vendor_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																中心反查
															</label>
															<div class="col-md-2">
																<input type="text" name="login_center_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_center_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																缓存
															</label>
															<div class="col-md-2">
																<input type="text" name="login_cached_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_cached_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
													</div>
												</div>
											</div>
	
											<div class="panel panel-default">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#statsAdvancedSearchMoal_tab3" style="text-decoration: none;">
														<h3 class="panel-title">
															数据接收
														</h3>
													</a>
												</div>
												<div id="statsAdvancedSearchMoal_tab3" class="panel-collapse collapse">
													<div class="panel-body">
														<div class="form-group">
															<label class="col-md-3 control-label">
																实名登录
															</label>
															<div class="col-md-2">
																<input type="text" name="login_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																实名登出
															</label>
															<div class="col-md-2">
																<input type="text" name="logout_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="logout_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																虚拟身份
															</label>
															<div class="col-md-2">
																<input type="text" name="vid_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="vid_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																上网数据
															</label>
															<div class="col-md-2">
																<input type="text" name="data_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="data_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																上网日志
															</label>
															<div class="col-md-2">
																<input type="text" name="conn_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="conn_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																感知数据
															</label>
															<div class="col-md-2">
																<input type="text" name="wls_rcvd_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="wls_rcvd_max" class="form-control input-width-mini">
															</div>
														</div>
													</div>
												</div>
											</div>
	
											<div class="panel panel-default">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#statsAdvancedSearchMoal_tab4" style="text-decoration: none;">
														<h3 class="panel-title">
															数据发送
														</h3>
													</a>
												</div>
												<div id="statsAdvancedSearchMoal_tab4" class="panel-collapse collapse">
													<div class="panel-body">
														<div class="form-group">
															<label class="col-md-3 control-label">
																实名登录
															</label>
															<div class="col-md-2">
																<input type="text" name="login_sent_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="login_sent_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																实名登出
															</label>
															<div class="col-md-2">
																<input type="text" name="logout_sent_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="logout_sent_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																虚拟身份
															</label>
															<div class="col-md-2">
																<input type="text" name="vid_sent_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="vid_sent_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																上网数据
															</label>
															<div class="col-md-2">
																<input type="text" name="data_sent_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="data_sent_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																上网日志
															</label>
															<div class="col-md-2">
																<input type="text" name="conn_sent_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="conn_sent_max" class="form-control input-width-mini">
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">
																感知数据
															</label>
															<div class="col-md-2">
																<input type="text" name="wls_sent_min" class="form-control input-width-mini">
															</div>
															<div class="col-md-1" style="text-align: center;">
																-
															</div>
															<div class="col-md-2">
																<input type="text" name="wls_sent_max" class="form-control input-width-mini">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							取消
						</button>
						<button type="submit" class="btn btn-info">
							搜索
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endcan
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
				appendText: '',
				dateFormat: "yy-mm-dd"
			});
		});
	</script>
@stop
