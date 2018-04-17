@extends('common.layouts')

@section('style')
	<style>
		.widget-content.no-padding .dataTables_header{
			border-top: 1px solid #ddd;
		}
	</style>
@stop

@section('menu')
	场所状态统计
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="widget box">
				<div class="widget-header">
					<h4>
						<i class="icon-reorder">
						</i>
						场所状态统计
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

				<div class="widget-content no-padding">
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
									场所分类
								</th>
								<th>
									场所总数
								</th>
								<th>
									在线数
								</th>
								<th>
									在线率
								</th>
							</tr>
						</thead>
						<tbody>
							
								<tr>
									<td>
										
									</td>
									<td>
										
									</td>
									<td>
										
									</td>
									<td>
										
									</td>
								</tr>
							<tfoot>
								<tr>
									<th>
										全部
									</th>
									<th>
										
									</th>
									<th>
										
									</th>
									<th>
										
									</th>
							</tfoot>
						</tbody>
					</table>
				</div>		
			</div>		
		</div>
	</div>
@stop
