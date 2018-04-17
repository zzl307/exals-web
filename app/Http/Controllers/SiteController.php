<?php

namespace App\Http\Controllers;

use App\Site;
use App\SiteDevice;
use Curl\Curl;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function home(Site $site)
	{
		$data = request()->all();
		if (!array_key_exists('key', $data)) {
			return View('site.sites');
		}

		if (empty($data['key'])) {
			$data['key'] = '';
		}

		if (empty($data['type'])) {
			$data['type'] = 0;
		}

		if (empty($data['list'])) {
			$data['list'] = 25;
		}

		if (empty($data['project_name'])) {
			$project_name = '';
		} else {
			$project_name = $data['project_name'];
		}

		// dd($data['project_name']);

		$query = "";
		foreach ($data as $key => $val) {
			if (!empty($query)) {
				$query .= '&';
			}
			$query .= $key.'='.$val;
		}

		$key = $data['key'];
		$type = $data['type'];
		$area_data = explode('/', $data['area']);
		$province = !empty($area_data[0]) ? $area_data[0] : '';
		$city = !empty($area_data[1]) ? $area_data[1] : '';
		$district = !empty($area_data[2]) ? $area_data[2] : '';

		if ($type == 1) {
			$cond = Site::getOnlineCond();
		} elseif ($type == 2) {
			$cond = Site::getOfflineCond();
		} elseif ($type == 3) {
			$cond = Site::getOnlineCond().' and '.Site::getNormalCond();
		} elseif ($type == 4) {
			$cond = Site::getOnlineCond().' and '.Site::getAbnormalCond();
		} elseif ($type == 5) {
			$cond = Site::getOnlineCond().' and '.Site::getUserNotFineCond();
		} elseif ($type == 6) {
			$cond = Site::getOnlineCond().' and '.Site::getVidNotFineCond();
		} elseif ($type == 7) {
			$cond = Site::getOnlineCond().' and '.Site::getDataNotFineCond();
		} elseif ($type == 8) {
			$cond = Site::getOnlineCond().' and '.Site::getConnNotFineCond();
		} elseif ($type == 9) {
			$cond = Site::getOnlineCond().' and '.Site::getwlsNotFineCond();
		} elseif ($type == 10) {
			$cond = Site::getOnlineCond().' and '.Site::getApAbnormalCond();
		} elseif ($type == 11) {
			$cond = Site::getSitesWithUnregisteredDevicesCond();
		} elseif ($type == 12) {
			$cond = Site::getOnlineCond().' and '.Site::getVendorStatsAbnormalCond();
		} elseif ($type == 13) {
			$cond = Site::getOnlineCond().' and '.Site::getUserTypeAbnormalCond();
		} elseif ($type == 14) {
			$cond = Site::getOnlineCond().' and '.Site::getMacNotFineCond();
		} else {
			$cond = 'true';
		}

		if (!empty($province)) {
			$cond = $cond . " and province like '%". $province. "%'";
		}

		if (!empty($city)) {
			$cond = $cond . " and city like '%". $city. "%'";
		}

		if (!empty($district)) {
			$cond = $cond . " and district like '%". $district. "%'";
		}

		if (!empty($project_name)) {
			$cond = $cond . " and project_name like '%". $project_name . "%'";
		}

		$sites = array();

		if (empty($key)) {
			foreach (Site::select('site_id')->whereRaw($cond)->paginate($data['list']) as $vo) {	
				$sites[] = Site::getSiteInfo($vo->site_id);
			}
			$total = Site::whereRaw($cond)->count();
			$paginator = new LengthAwarePaginator($sites, $total, $data['list'], null, [
				'path' => url('site/index?'.$query),
				'pageName' => 'page',
			]);
			return View('site.sites', compact('data', 'sites', 'total', 'paginator'));
		}

		if (substr($key, 0, 7) == 'secdev:') {
			$counts = array();
			foreach (SiteDevice::select('site_id', DB::raw('COUNT(device_id) as devices'))->where('device_type', 'secdev')->groupBy('site_id')->get() as $vo) {
				$counts[strtolower($vo->site_id)] = $vo->devices;
			}

			$ids = array();
			if ($key == 'secdev:0') {
				foreach (Site::select('site_id')->get() as $vo) {
					$site_id = strtolower($vo->site_id);
					if (!array_key_exists($site_id, $counts))
						$ids[] = $vo->site_id;
				}
			} elseif ($key == 'secdev:1') {
				foreach (Site::select('site_id')->get() as $vo) {
					$site_id = strtolower($vo->site_id);
					if (array_key_exists($site_id, $counts) && $counts[$site_id] == 1)
						$ids[] = $vo->site_id;
				}
			} elseif ($key == 'secdev:2') {
				foreach (Site::select('site_id')->get() as $vo) {
					$site_id = strtolower($vo->site_id);
					if (array_key_exists($site_id, $counts) && $counts[$site_id] > 1)
						$ids[] = $vo->site_id;
				}
			}
			$total = Site::select('site_id')->whereIn('site_id', $ids)->whereRaw($cond)->count();
			foreach (Site::select('site_id')->whereIn('site_id', $ids)->whereRaw($cond)->paginate($data['list']) as $vo) {
				$sites[] = Site::getSiteInfo($vo->site_id);
			}
			$paginator = new LengthAwarePaginator($sites, $total, $data['list'], null, [
				'path' => url('site/index?'.$query),
				'pageName' => 'page',
			]);

			return View('site.sites', compact('data', 'sites', 'total', 'paginator'));
		}

		$cond = $cond . " and (";
		$cond = $cond . "site_id like '". $key. "%'";
		$cond = $cond . " or ip_address = '". $key. "'";
		$cond = $cond . " or site_name like '%". $key. "%'";

		$mac = $key;
		// 检查是否是mac
		$mac = strtolower($mac);
		$mac = str_replace(":", "", $mac);
		$mac = str_replace("-", "", $mac);
		if (preg_match('/^[0-9a-f]{12}$/', $mac)) {
			$cond = $cond . " or site_id in (select site_id from site_device where device_id='". $mac. "')";
		}

		$cond = $cond . ")";

		$total = Site::whereRaw($cond)->count();
		foreach (Site::select('site_id')->whereRaw($cond)->paginate($data['list']) as $vo) {
			$sites[] = Site::getSiteInfo($vo->site_id);
		}
		
		$paginator = new LengthAwarePaginator($sites, $total, $data['list'], null, [
			'path' => url('site/index?'.$query),
			'pageName' => 'page',
		]);

		return View('site.sites', compact('data', 'sites', 'total', 'paginator'));
	}

	public function export()
	{
		$data = request()->all();
		$key = $data['key'];
		$type = $data['type'];
		$area_data = explode('/', $data['area']);
		$province = !empty($area_data[0]) ? $area_data[0] : '';
		$city = !empty($area_data[1]) ? $area_data[1] : '';
		$district = !empty($area_data[2]) ? $area_data[2] : '';

		if ($type == 1) {
			$cond = Site::getOnlineCond();
		} elseif ($type == 2) {
			$cond = Site::getOfflineCond();
		} elseif ($type == 3) {
			$cond = Site::getOnlineCond().' and '.Site::getNormalCond();
		} elseif ($type == 4) {
			$cond = Site::getOnlineCond().' and '.Site::getAbnormalCond();
		} elseif ($type == 5) {
			$cond = Site::getOnlineCond().' and '.Site::getUserNotFineCond();
		} elseif ($type == 6) {
			$cond = Site::getOnlineCond().' and '.Site::getVidNotFineCond();
		} elseif ($type == 7) {
			$cond = Site::getOnlineCond().' and '.Site::getDataNotFineCond();
		} elseif ($type == 8) {
			$cond = Site::getOnlineCond().' and '.Site::getConnNotFineCond();
		} elseif ($type == 9) {
			$cond = Site::getOnlineCond().' and '.Site::getwlsNotFineCond();
		} elseif ($type == 10) {
			$cond = Site::getOnlineCond().' and '.Site::getApAbnormalCond();
		} elseif ($type == 11) {
			$cond = Site::getSitesWithUnregisteredDevicesCond();
		} elseif ($type == 12) {
			$cond = Site::getOnlineCond().' and '.Site::getVendorStatsAbnormalCond();
		} elseif ($type == 13) {
			$cond = Site::getOnlineCond().' and '.Site::getUserTypeAbnormalCond();
		} elseif ($type == 14) {
			$cond = Site::getOnlineCond().' and '.Site::getMacNotFineCond();
		} else {
			$cond = 'true';
		}

		if (!empty($province)) {
			$cond = $cond . " and province like '%". $province. "%'";
		}

		if (!empty($city)) {
			$cond = $cond . " and city like '%". $city. "%'";
		}

		if (!empty($district)) {
			$cond = $cond . " and district like '%". $district. "%'";
		}

		if (empty($key)) {
			$sites = Site::whereRaw($cond)->get();
		} else {
			$sites = Site::where('site_id', $key)->get();

			if ($sites->count() == 0) {
				$sites = Site::where('ip_address', $key)->get();
			}

			if ($sites->count() == 0) {
				$mac = $key;
				// 检查是否是mac
				$mac = strtolower($mac);
				$mac = str_replace(":", "", $mac);
				$mac = str_replace("-", "", $mac);
				if (preg_match('/^[0-9a-f]{12}$/', $mac)) {
					$site_id = SiteDevice::getSiteId($mac);
					if ($site_id) {
						$sites = Site::where('site_id', $mac)->get();
					}
				}
			}

			if ($sites->count() == 0) {
				$sites = Site::whereRaw($cond." and site_name like '%".$key."%'")->get();
			}
		}

		$content = [ 0 =>
			['场所号', '场所名称', '项目名称', '省', '市', '区', '公网IP', '在线终端数', 'AP数量', '在线时间', '最后获取实名时间', '最后获取虚拟身份时间', '最后获取上网数据时间', '最后获取上网日志时间', '最后获取感知数据时间']
			];

		foreach ($sites as $site) {
			$a = array();
			$a[] = $site->site_id;
			$a[] = $site->site_name;
			$a[] = $site->project_name;
			$a[] = $site->province;
			$a[] = $site->city;
			$a[] = $site->district;
			$a[] = $site->ip_address;
			$a[] = $site->online_users;
			$a[] = \App\SiteDevice::where(['site_id' => $site->site_id, 'device_type' => 'ap'])->count();
			$a[] = $site->update_time;
			$a[] = $site->last_user_time;
			$a[] = $site->last_vid_time;
			$a[] = $site->last_data_time;
			$a[] = $site->last_conn_time;
			$a[] = $site->last_wls_time;
			$content[] = $a;
		}

        Excel::create('场所状态-'.date('YmdHis', time()), function ($excel) use ($content) {
	            $excel->sheet('score', function ($sheet) use ($content) { $sheet->rows($content);
            });
        })->export('xls');
	}

	// API
	public function deleteSites()
	{
		$sites = explode(',', request()->input('sites'));
		foreach ($sites as $site_id)
		{
			Site::destroy($site_id);
			\DB::table('site_device')
						->where('site_id', '=', $site_id)
						->delete();
            \DB::table('site_device_stats')
						->where('site_id', '=', $site_id)
						->delete();
			\DB::table('site_stats')
						->where('site_id', '=', $site_id)
						->delete();
			\DB::table('site_vendor_stats')
						->where('site_id', '=', $site_id)
						->delete();
		}
		$resp['sites'] = count($sites);
		return json_encode($resp);
	}

	public function getSiteInfo()
	{
		$site_id = request()->input('site_id');
		$site = Site::getSiteInfo($site_id);
		return json_encode($site);
	}

	public function syncSiteInfo()
	{
		$site_id = request()->input('site_id');
		
		$curl = new Curl();
		$curl->get('https://exadc.exands.com/api/v1.0/baseinfo?method=getSiteInfo&site_id='.$site_id);
		if ($curl->response->errcode == 0) {
			if (isset($curl->response->data->site)) {
				$siteData = isset($curl->response->data->site);
				$sync_status = 1;
				\DB::table('site')
						->where('site_id', '=', $curl->response->data->site_id)
						->update([
							'site_name' => $curl->response->data->site->name,
							'sync_time' => date('Y-m-d H:i:s', time()),
							'sync_status' => $sync_status
						]);
			}
			if (isset($curl->response->data->ap)) {
				$apData = $curl->response->data->ap;
				foreach ($apData as $vo) {
					try {
						\DB::table('site_device')
								->insert([
									'device_id' => strtolower($vo->apmac),
									'device_type' => 'ap',
									'site_id' => $site_id,
									'registered' => 1
								]);
					} catch (\Exception $e) {
						if ($e->getCode() == "23000") {
							\DB::table('site_device')
									->where('device_id', '=', strtolower($vo->apmac))
									->update([
										'device_type' => 'ap',
										'site_id' => $site_id,
										'registered' => 1
									]);
						}
							
					}
				}
			}
			if (isset($curl->response->data->secdev)) {
				$secdevData = $curl->response->data->secdev;
				foreach ($secdevData as $vo) {

					try {
						\DB::table('site_device')
								->insert([
									'device_id' => strtolower($vo->mac),
									'device_type' => 'secdev',
									'site_id' => $site_id,
									'registered' => 1
								]);
					} catch (\Exception $e) {
						if ($e->getCode() == "23000") {
							\DB::table('site_device')
									->where('device_id', '=', strtolower($vo->mac))
									->update([
										'device_type' => 'secdev',
										'site_id' => $site_id,
										'registered' => 1
									]);
						}
							
					}
				}
			}
		} elseif ($curl->response->errcode == 100) {
			$sync_status = 0;
			\DB::table('site')
					->where('site_id', '=', $site_id)
					->update([
						'sync_time' => date('Y-m-d H:i:s', time()),
						'sync_status' => $sync_status
					]);
		} else {
			$sync_status = -1;
			\DB::table('site')
					->where('site_id', '=', $site_id)
					->update([
						'sync_time' => date('Y-m-d H:i:s', time()),
						'sync_status' => $sync_status
					]);
		}

		$site = Site::getSiteInfo($site_id);

		return json_encode($site);
	}

	public function deleteSiteDevice()
	{
		$device_id = request()->input('device_id');
		SiteDevice::destroy($device_id);
		$site_id = request()->input('site_id');
		$site = Site::getSiteInfo($site_id);
		return json_encode($site);
	}

	public function setSiteAuthType()
	{
		$site_id = request()->input('site_id');
		$auth_type = request()->input('auth_type');

		$ids = explode(',', $site_id);
		if (count($ids) == 1)
		{
			Site::where('site_id', $site_id)->update(['auth_type' => $auth_type]);
			$site = Site::getSiteInfo($site_id);
			return json_encode($site);
		}
		else
		{
			Site::whereIn('site_id', $ids)->update(['auth_type' => $auth_type]);
			$resp['sites'] = count($ids);
			return json_encode($resp);
		}
	}

	public function setSiteFlags()
	{
		$site_id = request()->input('site_id');
		$login_udp_flag = request()->input('login_udp_flag');
		$login_radius_flag = request()->input('login_radius_flag');

		$ids = explode(',', $site_id);
		if (count($ids) == 1)
		{
			Site::where('site_id', $site_id)->update(['login_udp_flag' => $login_udp_flag, 'login_radius_flag' => $login_radius_flag]);
			$site = Site::getSiteInfo($site_id);
			return json_encode($site);
		}
		else
		{
			Site::whereIn('site_id', $ids)->update(['login_udp_flag' => $login_udp_flag, 'login_radius_flag' => $login_radius_flag]);
			$resp['sites'] = count($ids);
			return json_encode($resp);
		}
	}

	// 重新获取场所名称
	public function getSiteName()
	{
		$site_id = request()->input('site_id');
		
		$curl = new Curl();
		$curl->get('https://exadc.exands.com/api/v1.0/baseinfo/?method=getSiteInfo&site_id='.$site_id);
		if ($curl->response->errcode == 100) {
			$data['name'] = '没有数据';
			return response()->json($data);
		} else {
			$site_name = \App\Site::find($site_id);
			$site_name->site_name = $curl->response->data->site->name;
			if ($site_name->save()) {
				return response()->json($curl->response->data->site);
			}
		}		
	}

	public function getSiteNameInfo()
	{
		if (request()->isMethod('POST')) {
			$site_name = request()->input('site_name');
			$site_id = request()->input('site_id');

			$site = Site::find($site_id);
			$site->site_name = $site_name;
			if ($site->save()) {
				return $site_name;
			}	
		}
	}

	public function stats() {

		return View('site.stats');
	}

	public function statsSearch()
	{
		$data = request()->all();

		$cond = 'true';
		if (isset($data['date']))
			$cond .= ' and site_stats.date = "' . $data['date'] . '"';
		if (isset($data['site_id']))
			$cond .= ' and site_stats.site_id = "' . $data['site_id'] . '"';
		if (isset($data['site_name']))
			$cond .= ' and site.site_name like "%' . $data['site_name'] . '%"';

		$cond .= $this->getRangeCond($data, 'clients');
		$cond .= $this->getRangeCond($data, 'login_rcvd');
		$cond .= $this->getRangeCond($data, 'logout_rcvd');
		$cond .= $this->getRangeCond($data, 'vid_rcvd');
		$cond .= $this->getRangeCond($data, 'data_rcvd');
		$cond .= $this->getRangeCond($data, 'conn_rcvd');
		$cond .= $this->getRangeCond($data, 'wls_rcvd');
		$cond .= $this->getRangeCond($data, 'login_unknown_rcvd');
		$cond .= $this->getRangeCond($data, 'login_query_rcvd');
		$cond .= $this->getRangeCond($data, 'login_udp_rcvd');
		$cond .= $this->getRangeCond($data, 'login_portal_rcvd');
		$cond .= $this->getRangeCond($data, 'login_radius_auth_rcvd');
		$cond .= $this->getRangeCond($data, 'login_radius_acct_rcvd');
		$cond .= $this->getRangeCond($data, 'login_vendor_rcvd');
		$cond .= $this->getRangeCond($data, 'login_center_rcvd');
		$cond .= $this->getRangeCond($data, 'login_cached_rcvd');
		$cond .= $this->getRangeCond($data, 'login_sent');
		$cond .= $this->getRangeCond($data, 'logout_sent');
		$cond .= $this->getRangeCond($data, 'vid_sent');
		$cond .= $this->getRangeCond($data, 'data_sent');
		$cond .= $this->getRangeCond($data, 'conn_sent');
		$cond .= $this->getRangeCond($data, 'wls_sent');

		$total = DB::table('site_stats')
						->leftJoin('site', 'site_stats.site_id', '=', 'site.site_id')
						->whereRaw($cond)
						->count();
		$stats = DB::table('site_stats')
						->leftJoin('site', 'site_stats.site_id', '=', 'site.site_id')
						->whereRaw($cond)
						->select('site.site_name', 'site_stats.clients', 'site_stats.site_id', 'site_stats.login_rcvd', 'site_stats.logout_rcvd', 'site_stats.vid_rcvd', 'site_stats.data_rcvd', 'site_stats.conn_rcvd', 'site_stats.wls_rcvd', 'site_stats.login_unknown_rcvd', 'site_stats.login_query_rcvd', 'site_stats.login_udp_rcvd', 'site_stats.login_portal_rcvd', 'site_stats.login_radius_auth_rcvd', 'site_stats.login_radius_acct_rcvd', 'site_stats.login_vendor_rcvd', 'site_stats.login_center_rcvd', 'site_stats.login_cached_rcvd', 'site_stats.login_sent', 'site_stats.logout_sent', 'site_stats.vid_sent', 'site_stats.data_sent', 'site_stats.conn_sent', 'site_stats.wls_sent', 'site_stats.date')
						->orderBy('site_stats.date', 'desc')
						->paginate(25);

		$query = "";
		foreach ($data as $key => $val)
		{
			if (!empty($query))
				$query .= '&';
			$query .= $key.'='.$val;
		}

		$paginator = new LengthAwarePaginator($stats, $total, 25, null, [
				'path' => url('site/stats/search?'.$query),
				'pageName' => 'page',
		]);

		return View('site.stats', compact('stats', 'total', 'paginator', 'query'));
	}

	public function getRangeCond($data, $name)
	{
		$min = $name.'_min';
		$max = $name.'_max';

		$range = '';

		if (isset($data[$min]))
			$range = 'site_stats.'.$name.' >= '.$data[$min];

		if (isset($data[$max]))
			$range .= (empty($range) ? '' : ' and ').'site_stats.'.$name.' <= '.$data[$max];

		if (!empty($range))
			$range = ' and ('.$range.')';

		return $range;
	}

	// 场所统计导出
	public function siteExcel()
	{	
		$data = request()->all();

		$cond = 'true';
		if (isset($data['date']))
			$cond .= ' and site_stats.date = "' . $data['date'] . '"';
		if (isset($data['site_id']))
			$cond .= ' and site_stats.site_id = "' . $data['site_id'] . '"';
		if (isset($data['site_name']))
			$cond .= ' and site.site_name like "%' . $data['site_name'] . '%"';

		$cond .= $this->getRangeCond($data, 'clients');
		$cond .= $this->getRangeCond($data, 'login_rcvd');
		$cond .= $this->getRangeCond($data, 'logout_rcvd');
		$cond .= $this->getRangeCond($data, 'vid_rcvd');
		$cond .= $this->getRangeCond($data, 'data_rcvd');
		$cond .= $this->getRangeCond($data, 'conn_rcvd');
		$cond .= $this->getRangeCond($data, 'wls_rcvd');
		$cond .= $this->getRangeCond($data, 'login_unknown_rcvd');
		$cond .= $this->getRangeCond($data, 'login_query_rcvd');
		$cond .= $this->getRangeCond($data, 'login_udp_rcvd');
		$cond .= $this->getRangeCond($data, 'login_portal_rcvd');
		$cond .= $this->getRangeCond($data, 'login_radius_auth_rcvd');
		$cond .= $this->getRangeCond($data, 'login_radius_acct_rcvd');
		$cond .= $this->getRangeCond($data, 'login_vendor_rcvd');
		$cond .= $this->getRangeCond($data, 'login_center_rcvd');
		$cond .= $this->getRangeCond($data, 'login_cached_rcvd');
		$cond .= $this->getRangeCond($data, 'login_sent');
		$cond .= $this->getRangeCond($data, 'logout_sent');
		$cond .= $this->getRangeCond($data, 'vid_sent');
		$cond .= $this->getRangeCond($data, 'data_sent');
		$cond .= $this->getRangeCond($data, 'conn_sent');
		$cond .= $this->getRangeCond($data, 'wls_sent');

		$stats = DB::table('site_stats')
						->leftJoin('site', 'site_stats.site_id', '=', 'site.site_id')
						->whereRaw($cond)
						->select('site_stats.date', 'site.site_name', 'site_stats.clients', 'site_stats.site_id', 'site_stats.login_rcvd', 'site_stats.logout_rcvd', 'site_stats.vid_rcvd', 'site_stats.data_rcvd', 'site_stats.conn_rcvd', 'site_stats.wls_rcvd', 'site_stats.login_unknown_rcvd', 'site_stats.login_query_rcvd', 'site_stats.login_udp_rcvd', 'site_stats.login_portal_rcvd', 'site_stats.login_radius_auth_rcvd', 'site_stats.login_radius_acct_rcvd', 'site_stats.login_vendor_rcvd', 'site_stats.login_center_rcvd', 'site_stats.login_cached_rcvd', 'site_stats.login_sent', 'site_stats.logout_sent', 'site_stats.vid_sent', 'site_stats.data_sent', 'site_stats.conn_sent', 'site_stats.wls_sent')
						->orderBy('site_stats.date', 'desc')
						->get();

		$cellData = [ 0 =>
			['日期', '场所名称', '上网终端', '场所号', '实名上线数量', '实名下线数量', '虚拟身份数量', '上网数据数量', '上网日志数量', '感知数据数量', '未知实名数量', '反查实名数量', '消息实名数量', 'Portal实名数量', 'Radius认证实名数量', 'Radius计费实名数量', '第三方实名数量', '中心反查实名数量', '缓存实名数量', '实名上线数量', '实名下线数量', '虚拟身份数量', '上网数据数量', '上网日志数量', '感知数据数量']
			];
		$cellData = array_merge($cellData, $this->toArray($stats));
		$time = date('Y-m-d H:i:s', time());
        Excel::create('场所统计'.$time,function ($excel) use ($cellData){
            $excel->sheet('score', function ($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');

	}

	function toArray ($obj){
		return json_decode(json_encode($obj), true);
	}

	// 场所管理 
	public function siteManage()
	{	
		if (request()->isMethod('POST')) {
			$data = request()->all();

			$siteData = new \App\SiteIndex;
			$siteData->name = $data['name'];
			$siteData->keyword = $data['keyword'];

			if ($siteData->save()) {
				return redirect()->back()->with('success', '添加成功');
			} else {
				return redirect()->back()->with('error', '添加失败');
			}
		}
		$siteStatus = \DB::table('site_index')
								->get();

		$site_data = array();
		foreach ($siteStatus as $vo) {
			$site_data[$vo->name][] = $vo;
		}

		return View('site.siteManage', compact('site_data'));
	}

	// 场所管理修改
	public function editSiteManage()
	{	
		if (request()->isMethod('POST')) {
			$data = request()->all();

			$newKeyword = explode(',', $data['keyword']);
			$oldKeyword = \DB::table('site_index')
								->where('name', '=', $data['name'])
								->select('keyword')
								->get();

			$oldKeywordData = array();
			foreach ($oldKeyword as $key => $vo) {
				$oldKeywordData[] = $vo->keyword;
			}

			foreach (array_diff($newKeyword, $oldKeywordData) as $vo) {
				$siteData = new \App\SiteIndex;
				$siteData->name = $data['name'];
				$siteData->keyword = $vo;
				if ($siteData->save()) {
					return redirect()->back()->with('success', '添加成功');
				} else {
					return redirect()->back()->with('error', '添加失败');
				}
			}
		}
		$name = request()->input('name');
		$siteStatus = \DB::table('site_index')
								->where('name', '=', $name)
								->get();

		$site_data = array();
		foreach ($siteStatus as $vo) {
			$site_data[] = $vo->keyword;
		}

		return json_encode($site_data);
	}

	// 场所管理删除
	public function deleteSiteManage($name)
	{
		$siteData = \DB::table('site_index')
							->where('name', '=', $name)
							->delete();

		if ($siteData) {
			return redirect()->back()->with('success', '删除成功');
		} else {
			return redirect()->back()->with('error', '删除失败');
		}
	}

	public function getDeviceConfig()
	{
		$device_id = request()->input('device_id');
		
		$curl = new Curl();
		$curl->get('https://cc.exands.com/exals/config?devid='.$device_id);

		return json_encode(format_json($curl->response));
	}

	// 历史记录
	public function siteLogs()
	{	
		$data = request()->all();

		if (empty($data['site_id'])) {
			return View('site.logs');
		}
		$site_id = $data['site_id'];

		if (empty($data['date']))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		} else {
			$date = $data['date'];
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "' . $site_id . '"';

		$tbname = 'sitelog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('site.logs', compact('data'));

		$logs = DB::table($tbname)->whereRaw($cond)->orderBy('id', 'desc')->paginate(20);

		return View('site.logs', compact('logs', 'data'));
	}
}
