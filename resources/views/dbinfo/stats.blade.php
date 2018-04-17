@extends('common.layouts')

@section('menu')
	数据表概况
@stop

@section('content')

	<div class="row">
		<div class="col-md-12">
			
			@include('common.message')
			
			<div class="widget box" style="border: 0px;">
				<div class="widget-header" style="border: 1px solid #ddd;">
					<h4>
						<i class="icon-reorder">
						</i>
						数据存储概况
					</h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-refresh">
								<i class="icon-refresh">
								</i>
								刷新
							</span>
						</div>
					</div>
				</div>
				
				@if(isset($tables))
					<div class="widget-content no-padding">
						<div class="tabbable tabbable-custom">
							<ul class="nav nav-tabs" id="nav">
								<li class="active">
									<a href="#tab_summary" data-toggle="tab">
										统计
									</a>
								</li>
								@foreach($tables as $name => $stats)
									<li>
										<a href="#tab_{{ $name }}" data-toggle="tab" id="{{ $name }}">
											{{ $name }}
										</a>
									</li>
								@endforeach
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_summary">
									<table class="table table-hover table-striped table-bordered table-highlight-head" style="border: 1px solid #ddd;">
										<thead style="border-top: 1px solid #ddd;">
											<tr>
												<th>
													数据类型
												</th>
												<th>
													存储量
												</th>
												<th class="col-md-4">
													保存时间
												</th>
											</tr>
										</thead>
										<tbody>
											@foreach($tables as $name => $stats)
												<tr>
													<td>
														{{ $name }}
													</td>
													<td>
														{{ round($stats['total']/1024/1024) }}MB
													</td>
													<td>
														<span ondblclick="editDate('{{ $name }}')" id="{{ 'db_'.$name }}" class="bs-tooltip" title="双击修改数据库保存时间">
															{{ $stats['date'] }}
														</span>
														<span>
															天
														</span>
													</td>
												</tr>
											@endforeach
										</tbody>
										
									</table>
									<div class="dataTables_footer clearfix" style="padding: 12px 0;border-top: 0px;">
										<div class="col-md-6">
											<div class="dataTables_info">
												<strong>总计: {{ round($total/1024/1024) }}MB</strong>
											</div>
										</div>
									</div>
								</div>
								@foreach($tables as $name => $stats)
									<div class="tab-pane" id="tab_{{ $name }}">
										<table class="table table-hover table-striped table-bordered table-highlight-head" style="border: 1px solid #ddd;">
											<thead style="border-top: 1px solid #ddd;">
												<tr>
													<th>
														数据表
													</th>
													<th>
														存储量
													</th>
													<th>
														记录数
													</th>
													@can('data_manage')
														<th>
														</th>
													@endcan
												</tr>
											</thead>
											<tbody>
												@if(isset($stats['tables']))
													@foreach ($stats['tables'] as $key => $vo)
														<tr>
															<td>
																{{ $vo['tbname'] }}
															</td>
															<td>
																{{ round($vo['bytes']/1024/1024) }}MB
															</td>
															<td id="dbinfo_{{ $name }}_{{ $key }}">
																<span class="throbber-loader">Loading&#8230;</span>
															</td>
															@can('data_manage')
																<td>
																	<a class="btn btn-xs" href="{{ url('dbinfo/deleteDbInfo?tbname='.$vo['tbname']) }}" onclick="if(confirm('{{"确定删除?"}}') == false) return false;">
																		<i class="icon-trash" title="删除">
																		</i>
																	</a>
																</td>
															@endcan
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="text/javascript">
		$('#nav a').on('shown.bs.tab', function(e){
			var name = e.delegateTarget.id;
			$.getJSON('{{ url('dbinfo/getDbInfo') }}', {name: name}, function(data){
				for(var i = 0; i < data.length; i++){
					$('#dbinfo_'+name+'_'+i).html(data[i].records);
				}
			});
		});

		function editDate(name){
			var date = $.trim($('#db_'+name+'').text());
			var name = 'db_'+name;
			var dbName = 'dbName';
			$('#'+name+'').html("<input type='text' id='"+dbName+"' name='"+dbName+"' value='"+date+"' class='form-control input-width-mini' style='vertical-align: unset;display: unset;'>");
			$('#dbName').focus();
			$('#dbName').blur(function(){
				var db_date = $(this).val();
				if(db_date < 1 || db_date > 30){
					alert('请填写1天~30天保存时间');
					$('#dbName').remove();
					$('#'+name+'').text(date);
					return false;
				}
				if (db_date == ''){
					$(this).val('0');
				}else{
					$('#dbName').remove();
					$.post('{{ url('dbinfo/getDbDate') }}', {_token: '{{ csrf_token() }}', name: name, date: db_date}, function(data){
						$('#'+name+'').text(data);
					});
				}
			});
		}
	</script>
@stop
