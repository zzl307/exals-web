<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MacMonitor extends Model
{
	// 设置模型关联表
	protected $table = 'mac_monitor';

	protected $primaryKey = 'mac';
	protected $keyType = 'string';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置时间戳
	protected function getDateFormat(){
		return time();
	}

	public static function getMonitoredClients()
	{
		$clients = \DB::select('select mac_monitor.mac, clients.user_id, clients.id_type, clients.first_login, clients.last_login from mac_monitor left join clients on mac_monitor.mac=clients.mac');
		return $clients;
	}
}
