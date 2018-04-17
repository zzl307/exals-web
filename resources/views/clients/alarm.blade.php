@extends('common.layouts')

@section('style')
	<style>
		code{
			display: block;
			float: left;
			padding: 0 8px;
			margin: 5px 5px 5px 5px;
			line-height: 23px;
			font-size: 11px;
			border: 0px;
			background: #fff;
		}
	</style>
@stop

@section('menu')
	报警记录
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
						报警记录
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
								<input id="user" class="form-control" name="user" value="{{ isset($data['user']) ? $data['user'] : '' }}" type="text" placeholder="用户名 / 终端MAC">
							</div>
							<button class="btn btn-sm" style="padding: 5px 16px;">搜索</button>
						</div>
					</form>
				</div>
				@if($alarm->isEmpty())
					<div class="widget-content no-padding">
						<code>
							TODO: 显示一些报警信息！
						</code>
					</div>
				@else
					<div class="widget-content no-padding">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead style="border-top: 1px solid #ddd;">
								<tr>
									<th>
										报警时间
									</th>
									<th>
										终端MAC
									</th>
									<th>
										用户身份
									</th>
									<th>
										用户身份类型
									</th>
									<th>
										场所号
									</th>
									<th>
										场所名称
									</th>
									<th>
										原因
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($alarm as $vo)
									<tr>
										<td>
											{{ $vo->time }}
										</td>
										<td>
											{{ $vo->mac }}
										</td>
										<td>
											{{ $vo->user_id }}
										</td>
										<td>
											{{ $vo->id_type }}
										</td>
										<td>
											{{ $vo->site_id }}
										</td>
										<td>
											{{ \App\Sites::getSiteName($vo->site_id) }}
										</td>
										<td>
											{{ $vo->reason }}
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
									{{ $alarm->render() }}
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
@stop
