<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteDeviceStats extends Model
{
	// 设置模型关联表
	protected $table = 'site_device_stats';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'id';

	public static function getDeviceStats($device_id, $date = null)
	{
		if ($date == null)
			$date = date('Y-m-d');

		return SiteDeviceStats::where(['device_id' => $device_id, 'date' => $date])->first();
	}
}
