<style>
	#sidebar{
		width: 206px;
	}
	#sidebar ul#nav ul.sub-menu a{
		padding: 12px 15px 12px 46px;
	}
	#content{
        margin-left: 206px;
    }
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td{
        vertical-align: middle;
    }
</style>

<!-- 左侧导航栏 -->
<ul id="nav">
	<!-- <li class="@if(Request::segment(1) == 'system') current @endif">
		<a href="{{ url('system') }}">
			<i class="icon-dashboard">
			</i>
			系统设置
		</a>
	</li> -->
	@can('site_overview')
	<li class="@if(Request::segment(1) == 'site') current @endif">
		<a href="javascript:void(0)">
			<i class="icon-building">
			</i>
			审计场所
		</a>
		<ul class="sub-menu">
			<li class="@if(Request::segment(1) == 'site' && Request::segment(2) == 'index') current @endif">
				<a href="{{ url('site/index') }}">
					<i class="icon-dashboard">
					</i>
					场所状态
				</a>
			</li>
			<li class="@if(Request::segment(1) == 'site' && Request::segment(2) == 'stats') current @endif">
				<a href="{{ url('site/stats') }}">
					<i class="icon-bar-chart">
					</i>
					场所统计
				</a>
			</li>
			<!-- <li class="@if(Request::segment(1) == 'site' && Request::segment(2) == 'siteManage') current @endif">
				<a href="{{ url('site/siteManage') }}">
					<i class="icon-desktop">
					</i>
					场所管理
				</a>
			</li> -->	
			<li class="@if(Request::segment(1) == 'site' && Request::segment(2) == 'siteLogs') current @endif">
				<a href="{{ url('site/siteLogs') }}">
					<i class="icon-list">
					</i>
					历史记录
				</a>
			</li>
		</ul>
	</li>
	@endcan

	@can('client_query')
		<li class="@if(Request::segment(1) == 'client') current @endif">
			<a href="javascript:void(0)">
				<i class="icon-user">
				</i>
				终端用户
			</a>
			<ul class="sub-menu">
				@can('client_query')
					<li class="@if(Request::segment(1) == 'client' && Request::segment(2) == 'logs') current @endif">
						<a href="{{ url('client/logs') }}">
							<i class="icon-caret-right">
							</i>
							访问记录
						</a>
					</li>
				@endcan
				@can('client_uid_query')
					<li class="@if(Request::segment(1) == 'client' && Request::segment(2) == 'list') current @endif">
						<a href="{{ url('client/list') }}">
							<i class="icon-caret-right">
							</i>
							实名信息
						</a>
					</li>
				@endcan
				@can('client_alert_query')
					<li class="@if(Request::segment(1) == 'client' && Request::segment(2) == 'alert') current @endif">
						<a href="{{ url('client/alert') }}">
							<i class="icon-caret-right">
							</i>
							敏感人员
						</a>
					</li>
				@endcan
				@can('client_alarm_qeury')
					<li class="@if(Request::segment(1) == 'client' && Request::segment(2) == 'alarm') current @endif">
						<a href="{{ url('client/alarm') }}">
							<i class="icon-caret-right">
							</i>
							报警记录
						</a>
					</li>
				@endcan
				<li class="@if(Request::segment(1) == 'client' && Request::segment(2) == 'screenshot') current @endif">
					<a href="{{ url('client/screenshot') }}">
						<i class="icon-caret-right">
						</i>
						访问频次
					</a>
				</li>
			</ul>
		</li>
	@endcan
	
	@can('log_query')
		<li class="@if(Request::segment(1) == 'logs') current @endif">
			<a href="javascript:void(0);">
				<i class="icon-search">
				</i>
				审计数据
			</a>
			<ul class="sub-menu">
				<li class="@if(Request::segment(2) == 'user') current @endif">
					<a href="{{url('logs/user')}}">
						<i class="icon-user">
						</i>
						用户数据
					</a>
				</li>
				<li class="@if(Request::segment(2) == 'http') current @endif">
					<a href="{{ url('logs/http') }}">
						<i class="icon-link">
						</i>
						上网数据
					</a>
				</li>
				<li class="@if(Request::segment(2) == 'conn') current @endif">
					<a href="{{ url('logs/conn') }}">
						<i class="icon-random">
						</i>
						上网日志
					</a>
				</li>
				<li class="@if(Request::segment(2) == 'wls') current @endif">
					<a href="{{ url('logs/wls') }}">
						<i class="icon-signal">
						</i>
						感知数据
					</a>
				</li>
			</ul>
		</li>
	@endcan
	
	@can('log_compare')
		<li class="@if(Request::segment(1) == 'match') current @endif">
			<a href="{{url('match')}}">
				<i class="icon-exchange">
				</i>
				数据比对
			</a>
		</li>
	@endcan
	
	@can('pb_log_query')
		<li class="@if(Request::segment(1) == 'paibo' or Request::segment(1) == 'nbx') current @endif">
			<a href="javascript:void(0)">
				<i class="icon-building">
				</i>
				数据分析
			</a>
			<ul class="sub-menu">
				<li class="@if(Request::segment(1) == 'paibo') current @endif">
					<a href="{{url('paibo')}}">
						<i class="icon-caret-right">
						</i>
						派博数据
					</a>
				</li>
				<li class="@if(Request::segment(1) == 'nbx') open-default @endif">
					<a href="javascript:;">
						<i class="icon-caret-right">
						</i>
						诺必行数据
					</a>
					<ul class="sub-menu">
						<li class="@if(Route::currentRouteName() == 'nbx.index') current @endif">
							<a href="{{ route('nbx.index') }}">
								<i class="icon-search">
								</i>
								数据统计
							</a>
						</li>
						<li class="@if(Route::currentRouteName() == 'nbx.create') current @endif">
							<a href="{{ route('nbx.create') }}">
								<i class="icon-edit">
								</i>
								数据配置
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	@endcan

	@can('data_overview')
		<li class="{{ Request::segment(1) == 'dbinfo' ? 'current' : ''}}">
			<a href="{{ url('dbinfo') }}">
				<i class="icon-hdd">
				</i>
				数据库概况
			</a>
		</li>
	@endcan
	
	@can('user_config')
		<li class="{{Request::segment(1) == 'user' ? 'current' : ''}}">
			<a href="javascript:void(0);">
				<i class="icon-group">
				</i>
				用户管理
			</a>
			<ul class="sub-menu">
				<li class="{{Request::segment(2) == 'list' ? 'current' : ''}}">
					<a href="{{ url('user/list') }}">
						<i class="icon-caret-right">
						</i>
						用户列表
					</a>
				</li>
				<li class="{{Request::segment(2) == 'roles' ? 'current' : ''}}">
					<a href="{{url('user/roles')}}">
						<i class="icon-caret-right">
						</i>
						角色设置
					</a>
				</li>
				<li class="{{Request::segment(2) == 'permission' ? 'current' : ''}}">
					<a href="{{url('user/permission')}}">
						<i class="icon-caret-right">
						</i>
						权限设置
					</a>
				</li>
			</ul>
		</li>
	@endcan 
</ul>
