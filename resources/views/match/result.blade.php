@extends('common.layouts')

@section('menu')
	数据比对
@stop

@section('content')

	<div class="row">
		<div class="col-md-12">
			
			@include('common.message')

			<div class="widget-content">
				<div class="row">
						<div class="tabbable tabbable-custom">

							<ul class="nav nav-tabs tabs-left">
								<li class="active">
									<a href="#tab_1" data-toggle="tab">
										上线记录比对结果
									</a>
								</li>
								<li>
									<a href="#tab_2" data-toggle="tab">
										终端记录比对结果
									</a>
								</li>
							</ul>

							<div class="tab-content">

								<div class="tab-pane active" id="tab_1">
									<div class="widget">
										<div class="widget-header" style="border-top: 0px solid #d9d9d9;">
											<h4>
												<i class="icon-reorder">
												</i>
												实名记录比对结果 （记录总数：{{ $total }}，未匹配：{{ $unmatch }} ）
											</h4>
										</div>
										<div class="widget-content ">
											<table class="table table-hover table-striped table-bordered table-highlight-head datatable">
												<thead>
													<tr>
														<th>
															时间
														</th>
														<th>
															终端MAC
														</th>
														<th>
															用户身份
														</th>
														<th>
															结果
														</th>
													</tr>
												</thead>
												<tbody>
													@foreach($results as $r)
														<tr>
															<td>
																{{ $r['time'] }}
															</td>
															<td>
																{{ $r['mac'] }}
															</td>
															<td>
																{{ $r['user_id'] }}
															</td>
															<td>
																@if ($r['match'] == 0)
																	<span class="label label-danger">无</span>
																@else
																	<span class="label label-success">有</span>
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="tab_2">
									<div class="widget">
										<div class="widget-header" style="border-top: 0px solid #d9d9d9;">
											<h4>
												<i class="icon-reorder">
												</i>
												终端记录比对结果 （终端总数：{{ $clients }}，未匹配：{{ $unseen }} ）
											</h4>
										</div>
										<div class="widget-content">
											<table class="table table-hover table-striped table-bordered table-highlight-head datatable">
												<thead>
													<tr>
														<th>
															终端MAC
														</th>
														<th>
															用户身份
														</th>
														<th>
															结果
														</th>
													</tr>
												</thead>
												<tbody>
													@foreach($users as $k => $v)
														<tr>
															<td>
																{{ $k }}
															</td>
															<td>
																{{ $v['user_id'] }}
															</td>
															<td>
																@if ($v['seen'] == 0)
																	<span class="label label-danger">无</span>
																@else
																	<span class="label label-success">有</span>
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer no-padding">
				<a href="javascript:history.back(-1)">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						返回
					</button>
				</a>
			</div>

		</div>
	</div>
@stop
