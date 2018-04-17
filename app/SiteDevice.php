<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteDevice extends Model
{
	// 设置模型关联表
	protected $table = 'site_device';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'device_id';
	protected $keyType = 'string';

	protected $dates = [ 'online_time', 'update_time', 'last_user_time', 'last_vid_time', 'last_data_time', 'last_conn_time', 'last_wls_time' ];

	public static function getSiteId($device_id)
	{
		$r = SiteDevice::find($device_id);
		if (!$r)
			return null;
		return $r->site_id;
	}

	public static function getSiteDevices($site_id)
	{
		return SiteDevice::where('site_id', $site_id)->get();
	}

	public static function isApNormal($dev)
	{
		if ($dev['last_user_time'] + 36000 < time())
			return false;
//		if ($dev['last_wls_time'] + 3600 < time())
//			return false;
		return true;
	}
}
