<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'IndexController@index');
Route::get('/', function () {
	return redirect('site/index');
});

// 登录
Auth::routes();

// 后台首页
// Route::get('/home', 'IndexController@index');
Route::get('/home', function () {
	return redirect('site/index');
});

// 系统设置
Route::any('system', 'SystemController@systemConfig');

// 审计场所
Route::group(['prefix' => 'site'], function () {
	Route::group(['middleware' => 'can:site_overview'], function () {
		Route::any('index', 'SiteController@home');
		Route::any('export', 'SiteController@export');
		// API
		Route::any('getSiteInfo', 'SiteController@getSiteInfo');
		Route::any('syncSiteInfo', 'SiteController@syncSiteInfo');
		Route::group(['middleware' => 'can:site_manage'], function () {
			Route::any('setSiteAuthType', 'SiteController@setSiteAuthType');
			Route::any('setSiteFlags', 'SiteController@setSiteFlags');
			Route::any('deleteSites', 'SiteController@deleteSites');
			Route::any('deleteSiteDevice', 'SiteController@deleteSiteDevice');
		});
		Route::any('getSiteName', 'SiteController@getSiteName');
		Route::any('getSiteNameInfo', 'SiteController@getSiteNameInfo');
		Route::any('stats', 'SiteController@stats');
		Route::any('stats/search', 'SiteController@statsSearch');
		// 场所统计导出
		Route::any('siteExcel', 'SiteController@siteExcel');
		// 场所管理
		Route::any('siteManage', 'SiteController@siteManage');
		// 场所管理修改
		Route::any('editSiteManage', 'SiteController@editSiteManage');
		// 场所管理删除
		Route::any('deleteSiteManage/{name}', 'SiteController@deleteSiteManage');	
		Route::any('getDeviceConfig', 'SiteController@getDeviceConfig');
		// 历史记录
		Route::any('siteLogs', 'SiteController@siteLogs');
	});
});

// 终端用户
Route::group(['prefix' => 'client'], function () {
	// 终端实名产讯
	Route::group(['middleware' => 'can:client_uid_query'], function () {
		Route::group(['prefix' => 'list'], function () {
			Route::get('/', 'ClientController@home');
			Route::group(['middleware' => 'can:client_uid_manage'], function () {
				Route::get('add', 'ClientController@showAddClientForm')->name('showAddClientForm');
				Route::post('add', 'ClientController@addClient')->name('addClient');
				Route::post('delete', 'ClientController@delClient')->name('delClient');
			});
		});
	});
	// 终端记录
	Route::group(['middleware' => 'can:client_query'], function () {
		Route::any('logs', 'ClientController@logs');
	});
	// 敏感人员
	Route::group(['middleware' => 'can:client_alert_query'], function () {
		Route::group(['prefix' => 'alert'], function () {
			Route::get('/', 'ClientController@alert');
			Route::group(['middleware' => 'can:client_alert_manage'], function () {
				Route::get('add', 'ClientController@showAddAlertForm');
				Route::post('add', 'ClientController@addAlert')->name('addAlert');
				Route::post('delete', 'ClientController@delAlert')->name('delAlert');
			});
		});
	});
	// 报警记录
	Route::group(['middleware' => 'can:client_alarm_qeury'], function () {
		Route::any('alarm', 'ClientController@alarm');
	});
	Route::any('update/{mac}', 'ClientController@update');
	Route::any('delete/{mac}', 'ClientController@delete');
	// 终端频次
	Route::any('screenshot', 'ClientController@screenshot');
	Route::any('macSite', 'ClientController@macSite');
});


Route::group(['prefix' => 'mac_monitor'], function () {
	Route::any('/', 'MacMonitorController@main');
	Route::any('add', 'MacMonitorController@add');
	Route::any('delete/{mac}', 'MacMonitorController@delete');
});

// 审计数据
Route::group(['middleware' => 'can:log_query'], function () {
	Route::group(['prefix' => 'logs'], function () {
		Route::any('user', 'LogsController@userlogs');
		// 虚拟身份统计
		Route::any('idTypeCount', 'LogsController@idTypeCount');
		Route::any('http', 'LogsController@httplogs');
		// 上网数据导出
		Route::any('export', 'LogsController@export');
		Route::any('conn', 'LogsController@connlogs');
		Route::any('wls', 'LogsController@wlslogs');
	});
});

// 数据对比
Route::group(['middleware' => 'can:log_compare'], function () {
	Route::group(['prefix' => 'match'], function () {
		Route::any('/', 'MatchController@home');
		Route::any('do', 'MatchController@match');
	});
});

// 派博数据
Route::group(['middleware' => 'can:pb_log_query'], function () {
	Route::group(['prefix' => 'paibo'], function () {
		Route::any('/', 'PaiboController@main');
		Route::any('search', 'PaiboController@search');
		Route::any('log/{id}/{date}', 'PaiboController@log');
	});
	// 诺必行数据
	Route::group(['prefix' => 'nbx'], function () {
		Route::any('/index', 'NbxConfigController@index')->name('nbx.index');
		Route::any('/show', 'NbxConfigController@show')->name('nbx.show');
		Route::any('/create', 'NbxConfigController@create')->name('nbx.create');
		Route::any('/store', 'NbxConfigController@store')->name('nbx.store');
	});
	
});

Route::group(['middleware' => 'can:data_overview'], function () {
	Route::group(['prefix' => 'dbinfo'], function () {
		Route::any('/', 'DbInfoController@showDbInfo');
		Route::any('getDbInfo', 'DbInfoController@getDbInfo');
		// 删除数据表
		Route::any('deleteDbInfo', 'DbInfoController@deleteDbInfo');
		// 修改数据表保存时间
		Route::any('getDbDate', 'DbInfoController@getDbDate');
	});
});

Route::group(['middleware' => 'can:user_config'], function () {
	// 用户管理权限
	Route::group(['prefix' => 'user'], function () {
		Route::any('list', 'UserController@userList');
		Route::any('add', 'UserController@addUser');
		Route::any('resetPassword', 'UserController@resetUserPassword');
		Route::any('getUserRoles', 'UserController@getUserRoles');
		Route::any('setUserRoles', 'UserController@setUserRoles');
		Route::any('delete', 'UserController@deleteUser');
		Route::any('roles', 'UserController@roles');
		Route::any('roles/add', 'UserController@addRole');
		Route::any('role', 'UserController@getRole');
		Route::any('role/update', 'UserController@updateRole');
		Route::any('role/delete', 'UserController@deleteRole');
		Route::any('role/getRolePermissions', 'UserController@getRolePermissions');
		Route::any('role/setRolePermissions', 'UserController@setRolePermissions');
		Route::any('permission', 'UserController@permissions');
		Route::any('permission/add', 'UserController@addPermission');
		Route::any('permission/update', 'UserController@updatePermission');
		Route::any('permission/delete', 'UserController@deletePermission');
	});
});

// 修改密码
Route::any('/resetPassword', 'UserController@resetPassword');

// 场所报警
Route::any('/sitePolice', 'IndexController@sitePolice');

// 查询
Route::any('/extern/check', 'CheckController@check');
