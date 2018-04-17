<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteStats extends Model
{
	// 设置模型关联表
	protected $table = 'site_stats';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'id';

	public static function getSiteStats($site_id, $date = null)
	{
		if ($date == null)
			$date = date('Y-m-d');

		return SiteStats::where(['site_id' => $site_id, 'date' => $date])->first();
	}
}
