<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sites extends Model
{	
	// 设置常量
	const OFF_LINE = 'off_line';	// 离线
	const UN_KNOWN = 'unknown';	// 无实名用户
	const UN_IDENTITY = 'unidentity';	// 无虚拟身份
	const UN_DATA = 'undata';	// 无上网数据
	const UN_LOG = 'unlog';	// 无上网日志
	const UN_PERCEPTION = 'unperception';	// 无感知数据

	// 设置模型关联表
	protected $table = 'site';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'site_id';
	protected $keyType = 'string';

	public static function status($site)
	{	
		// 数组转化对象
		if (is_array($site)) {
			$site = (object)$site;
		}

		$updateTime = strtotime($site->update_time);
		$lastUserTime  = strtotime($site->last_user_time);
		$lastVidTime  = strtotime($site->last_vid_time);
		$lastDataTime  = strtotime($site->last_data_time);
		$lastConnTime  = strtotime($site->last_conn_time);
		$lastWlsTime  = strtotime($site->last_wls_time);

		$now = time();

		if ($updateTime + 6000 < time())
			return "off_line";
		elseif ($lastUserTime + 36000 < time())
			return "unknown";
		elseif ($lastVidTime + 36000 < time())
			return "unidentity";
		elseif ($lastDataTime + 36000 < time())
			return "undata";
		elseif ($lastConnTime + 36000< time())
			return "unlog";
		elseif ($lastWlsTime + 6000 < time())
			return "unperception";
		else
			return "正常";
	}

	public static function getStatus($status = null)
	{
		$arr = [
			self::OFF_LINE => '离线',
			self::UN_KNOWN => '无实名用户',
			self::UN_IDENTITY => '无虚拟身份',
			self::UN_DATA => '无上网数据',
			self::UN_LOG => '无上网日志',
			self::UN_PERCEPTION => '无感知数据'
		];

		if ($status !== null) {
			return array_key_exists($status, $arr) ? $arr[$status] : '正常';
		}

		return $arr;
	}

	public static function getSiteName($site_id)
	{	
		if (isset($site_id)) {
			$data = DB::table('site')
						->where('site_id', '=', $site_id)
						->first();

			return $data->site_name;
		}
	}

	public static function isOnline($site)
	{	
		$t = strtotime($site->update_time);
		return ($t + 600 > time());
	}

	public static function isUserFine($site)
	{	
		$t = strtotime($site->last_user_time);
		return ($t + 3600 > time());
	}

	public static function isVidFine($site)
	{	
		$t = strtotime($site->last_vid_time);
		return ($t + 3600 > time());
	}

	public static function isDataFine($site)
	{	
		$t = strtotime($site->last_data_time);
		return ($t + 3600 > time());
	}

	public static function isConnFine($site)
	{	
		$t = strtotime($site->last_conn_time);
		return ($t + 3600 > time());
	}

	public static function isWlsFine($site)
	{	
		$t = strtotime($site->last_wls_time);
		return ($t + 600 > time());
	}
}
