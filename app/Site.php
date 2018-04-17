<?php

namespace App;

use App\SiteDevice;
use App\SiteStats;
use App\SiteDeviceStats;
use App\SiteVendorStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Site extends Model
{
	// 设置模型关联表
	protected $table = 'site';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'site_id';
	protected $keyType = 'string';

	protected $dates = [ 'update_time', 'last_user_time', 'last_vid_time', 'last_data_time', 'last_conn_time', 'last_wls_time' ];

	public static function isOnline($site)
	{
		return ($site['update_time'] + 600 > time());
	}

	public static function getOnlineCond()
	{
		return "update_time + interval 600 second >= now()";
	}

	public static function getOfflineCond()
	{
		return "(update_time = 0 or update_time + interval 600 second < now())";
	}

	public static function getApAbnormalCond()
	{
		return "(auth_type = 1 and site_id in (select site_id from site_device where device_type='ap' and registered=1 and last_user_time + 36000 < now()))";
	}

	public static function getSitesWithUnregisteredDevicesCond()
	{
		return "site_id in (select site_id from site_device where registered=0)";
	}

	public static function getVendorStatsAbnormalCond()
	{
		return "site_id in (select site_vendor_stats.site_id from site_vendor_stats left join site_stats on site_vendor_stats.site_id=site_stats.site_id and site_vendor_stats.date=site_stats.date where site_vendor_stats.date=curdate() and (site_stats.login_sent=0 or site_vendor_stats.login_sent=0 or site_vendor_stats.login_sent / site_stats.login_sent < 0.8))";
	}

	public static function getUserTypeAbnormalCond()
	{
		return "site_id in (select site.site_id from site left join site_stats on site.site_id=site_stats.site_id and site_stats.date=curdate() where (site.login_udp_flag=1 and site_stats.login_udp_rcvd=0) or (site.login_radius_flag=1 and site_stats.login_radius_auth_rcvd=0))";
	}

	public static function isAbnormal($site)
	{
		if (!Site::isUserFine($site))
			return true;
		if (!Site::isVidFine($site))
			return true;
		if (!Site::isDataFine($site))
			return true;
		if (!Site::isConnFine($site))
			return true;
		if (!Site::isWlsFine($site))
			return true;
		return false;
	}

	public static function getNormalCond()
	{
		return '('.Site::getUserFineCond().' and '.Site::getVidFineCond().' and '.Site::getDataFineCond().' and '.Site::getConnFineCond().' and '.Site::getWlsFineCond().')';
	}

	public static function getAbnormalCond()
	{
		return '('.Site::getUserNotFineCond().' or '.Site::getVidNotFineCond().' or '.Site::getDataNotFineCond().' or '.Site::getConnNotFineCond().' or '.Site::getWlsNotFineCond().')';
	}

	public static function isUserFine($site)
	{
		return ($site['last_user_time'] + 3600 > time());
	}

	public static function getUserFineCond()
	{
		return "last_user_time + interval 3600 second >= now()";
	}

	public static function getUserNotFineCond()
	{
		return "(last_user_time = 0 or last_user_time + interval 3600 second < now())";
	}

	public static function isVidFine($site)
	{
		return ($site['last_vid_time'] + 3600 > time());
	}

	public static function getVidFineCond()
	{
		return "last_vid_time + interval 3600 second >= now()";
	}

	public static function getVidNotFineCond()
	{
		return "(last_vid_time = 0 or last_vid_time + interval 3600 second < now())";
	}

	public static function isDataFine($site)
	{
		return ($site['last_data_time'] + 3600 > time());
	}

	public static function getDataFineCond()
	{
		return "last_data_time + interval 3600 second >= now()";
	}

	public static function getDataNotFineCond()
	{
		return "(last_data_time = 0 or last_data_time + interval 3600 second < now())";
	}

	public static function isConnFine($site)
	{
		return ($site['last_conn_time'] + 3600 > time());
	}

	public static function getConnFineCond()
	{
		return "last_conn_time + interval 3600 second >= now()";
	}

	public static function getConnNotFineCond()
	{
		return "(last_conn_time = 0 or last_conn_time + interval 3600 second < now())";
	}

	public static function isWlsFine($site)
	{
		return ($site['last_wls_time'] + 600 > time());
	}

	public static function getWlsFineCond()
	{
		return "last_wls_time + interval 600 second >= now()";
	}

	public static function getWlsNotFineCond()
	{
		return "(last_wls_time = 0 or last_wls_time + interval 600 second < now())";
	}

	public static function getMacNotFineCond()
	{
		return "(gateway_mac = '000000000000')";
	}

	public static function getSiteInfo($site_id)
	{
		$site = Site::find($site_id);
		if (!$site)
			return null;

		$siteinfo = array();
		$siteinfo['timestamp'] = time();
		$siteinfo['site_id'] = $site_id;
		$siteinfo['site_name'] = $site->site_name;
		$siteinfo['auth_type'] = $site->auth_type;
		$siteinfo['ip_address'] = $site->ip_address;
		$siteinfo['online_users'] = $site->online_users;
		$siteinfo['gateway_mac'] = $site->gateway_mac;
		$siteinfo['update_time'] = $site->update_time->getTimestamp();
		$siteinfo['last_user_time'] = $site->last_user_time->getTimestamp();
		$siteinfo['last_vid_time'] = $site->last_vid_time->getTimestamp();
		$siteinfo['last_data_time'] = $site->last_data_time->getTimestamp();
		$siteinfo['last_conn_time'] = $site->last_conn_time->getTimestamp();
		$siteinfo['last_wls_time'] = $site->last_wls_time->getTimestamp();
		$siteinfo['login_udp_flag'] = $site->login_udp_flag;
		$siteinfo['login_radius_flag'] = $site->login_radius_flag;
		$siteinfo['sync_time'] = $site->sync_time;
		$siteinfo['sync_status'] = $site->sync_status;
		$siteinfo['site_area'] = $site->province.$site->city.$site->district;
		$siteinfo['devices'] = array();
		$siteinfo['stats'] = array();
		$siteinfo['vendors'] = array();

		$devices = SiteDevice::getSiteDevices($site_id);
		foreach ($devices as $dev)
		{	
			$device = array();
			$device['device_id'] = $dev->device_id;
			$device['devtype'] = $dev->device_type;
			$device['registered'] = $dev->registered;;
			$device['online_time'] = $dev->online_time->getTimestamp();
			$device['update_time'] = $dev->update_time->getTimestamp();
			$device['version'] = $dev->version;
			$device['last_user_time'] = $dev->last_user_time->getTimestamp();
			$device['last_vid_time'] = $dev->last_vid_time->getTimestamp();
			$device['last_data_time'] = $dev->last_data_time->getTimestamp();
			$device['last_conn_time'] = $dev->last_conn_time->getTimestamp();
			$device['last_wls_time'] = $dev->last_wls_time->getTimestamp();
			$device['vendor'] = $dev->vendor;
			$device['modal'] = $dev->modal;

			$stats = SiteDeviceStats::getDeviceStats($dev->device_id);
			$device['login_rcvd'] = ($stats ? $stats->login_rcvd : 0);
			$device['logout_rcvd'] = ($stats ? $stats->logout_rcvd : 0);
			$device['vid_rcvd'] = ($stats ? $stats->vid_rcvd : 0);
			$device['data_rcvd'] = ($stats ? $stats->data_rcvd : 0);
			$device['conn_rcvd'] = ($stats ? $stats->conn_rcvd : 0);
			$device['wls_rcvd'] = ($stats ? $stats->wls_rcvd : 0);

			$siteinfo['devices'][] = $device;
		}

		$stats = SiteStats::getSiteStats($site_id);
		$a = array();
		$a['clients'] = ($stats ? $stats->clients : 0);
		$a['login_rcvd'] = ($stats ? $stats->login_rcvd : 0);
		$a['logout_rcvd'] = ($stats ? $stats->logout_rcvd : 0);
		$a['vid_rcvd'] = ($stats ? $stats->vid_rcvd : 0);
		$a['data_rcvd'] = ($stats ? $stats->data_rcvd : 0);
		$a['conn_rcvd'] = ($stats ? $stats->conn_rcvd : 0);
		$a['wls_rcvd'] = ($stats ? $stats->wls_rcvd : 0);
		$a['login_query_rcvd'] = ($stats ? $stats->login_query_rcvd : 0);
		$a['login_query_failed'] = ($stats ? $stats->login_query_failed : 0);
		$a['login_udp_rcvd'] = ($stats ? $stats->login_udp_rcvd : 0);
		$a['login_portal_rcvd'] = ($stats ? $stats->login_portal_rcvd : 0);
		$a['login_radius_auth_rcvd'] = ($stats ? $stats->login_radius_auth_rcvd : 0);
		$a['login_radius_acct_rcvd'] = ($stats ? $stats->login_radius_acct_rcvd : 0);
		$a['login_vendor_rcvd'] = ($stats ? $stats->login_vendor_rcvd : 0);
		$a['login_center_rcvd'] = ($stats ? $stats->login_center_rcvd : 0);
		$a['login_center_failed'] = ($stats ? $stats->login_center_failed : 0);
		$a['login_cached_rcvd'] = ($stats ? $stats->login_cached_rcvd : 0);
		$a['login_sent'] = ($stats ? $stats->login_sent : 0);
		$a['logout_sent'] = ($stats ? $stats->logout_sent : 0);
		$a['vid_sent'] = ($stats ? $stats->vid_sent : 0);
		$a['data_sent'] = ($stats ? $stats->data_sent : 0);
		$a['conn_sent'] = ($stats ? $stats->conn_sent : 0);
		$a['wls_sent'] = ($stats ? $stats->wls_sent : 0);
		$siteinfo['stats'] = $a;

		$vendors = SiteVendorStats::getSiteStats($site_id);
		foreach ($vendors as $v)
		{
			$a = array();
			$a['vendor'] = $v->vendor;
			$a['server'] = $v->server;
			$a['login_rcvd'] = $v->login_rcvd;
			$a['logout_rcvd'] = $v->logout_rcvd;
			$a['vid_rcvd'] = $v->vid_rcvd;
			$a['data_rcvd'] = $v->data_rcvd;
			$a['conn_rcvd'] = $v->conn_rcvd;
			$a['wls_rcvd'] = $v->wls_rcvd;
			$a['login_sent'] = $v->login_sent;
			$a['logout_sent'] = $v->logout_sent;
			$a['vid_sent'] = $v->vid_sent;
			$a['data_sent'] = $v->data_sent;
			$a['conn_sent'] = $v->conn_sent;
			$a['wls_sent'] = $v->wls_sent;
			$siteinfo['vendors'][] = $a;
		}

		return $siteinfo;
	}

	public static function isVendorStatsFine($site, $vendor)
	{	
		if ($site['stats']['login_sent'] > 0 && $vendor['login_sent'] > 0 && $site['stats']['vid_sent'] > 0 && $vendor['vid_sent'] > 0 && $site['stats']['conn_sent'] > 0 && $vendor['conn_sent'] > 0 && $site['stats']['wls_sent'] > 0 && $vendor['wls_sent'] > 0 && $site['stats']['logout_sent'] > 0 && $site['stats']['data_sent'] > 0) {
			if ($vendor['login_sent'] / $site['stats']['login_sent'] > 0.8 && $vendor['vid_sent'] / $site['stats']['vid_sent'] > 0.8 && $vendor['conn_sent'] / $site['stats']['conn_sent'] > 0.8 && $vendor['wls_sent'] / $site['stats']['wls_sent'] > 0.8) {
				return true;
			}
		}

		return false;
	}

	// 获取场所名称
	public static function getSiteName($site_id)
	{
		$siteName = \DB::table('site')
						->select('site_name')
						->where('site_id', '=', $site_id)
						->first();

		if (collect($siteName)->isEmpty()) {
            $siteName = '没有数据';
        } else {
            $siteName = $siteName->site_name;
        }

		return $siteName;
	}

	// 获取场所所有项目名称
	public static function getSiteArea()
	{
		$area_data = Site::select('project_name')
							->where('project_name', '!=', '')
							->distinct()
		                    ->get();

		return $area_data;
	}
}
