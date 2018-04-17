<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
	// 设置模型关联表
	protected $table = 'client';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'mac';
	protected $keyType = 'string';

	// 批量赋值字段
	protected $fillable = ['mac', 'user_id', 'id_type', 'first_login', 'last_login', 'site_id', 'persist'];

	// 设置时间戳
	protected function getDateFormat(){
		return time();
	}

	public static function getClientsByMac($mac)
	{
		return DB::table('client')->where('mac', '=', $mac)->get();
	}

	public static function getClientsByUserId($uid)
	{
		return DB::table('client')->where('user_id', '=', $uid)->get();
	}

	public static function getPersistClients()
	{
		return DB::table('client')->where('persist', '1')->get();
	}
}
