<?php

namespace App\Http\Controllers;

use App\Sites;
use Illuminate\Http\Request;
use Excel;
use \Curl\Curl;
use Illuminate\Support\Facades\DB;

class SitesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function home()
	{	
		$data = request()->all();
		if (empty($data))
			return View('sites.home');

		$cond = 'true';
		if (isset($data))
		{	
			if (!empty($data['site_id']))
				$cond .= ' and site.site_id = "' . $data['site_id'] . '"';

			if (!empty($data['site_name']))
				$cond .= ' and site_name like "%' . $data['site_name'] . '%"';

			if (!empty($data['device_id']))
				$cond .= ' and device_id = "' . $data['device_id'] . '"';

			if (!empty($data['ip_address']))
				$cond .= ' and site.ip_address = "' . $data['ip_address'] . '"';
		}

		$sites = \DB::table('site')
						->leftJoin('site_device', 'site.site_id', '=', 'site_device.site_id')
						->select('site.*', 'site_device.device_id', 'site_device.version')
						->whereRaw($cond)
						->get();
		return View('sites.home', compact('data', 'sites'));
	}

	public function detail($site_id)
	{
		$site = \DB::table('site')
						->leftJoin('site_device', 'site.site_id', '=', 'site_device.site_id')
						->where('site.site_id', '=', $site_id)
						->first();

		if ($site == null)
			redirect()->back()->with('error', '场所已经不存在');

		return View('sites.detail', compact('site'));
	}

	// 场所删除
	public function siteDelete($site_id)
	{
		$data = \App\Sites::find($site_id);

		$data_device = \DB::table('site_device')
								->where('site_id', '=', $site_id)
								->delete();

		if ($data->delete()) {
			return redirect()->back()->with('success', '场所删除成功');
		} else {
			return redirect()->back()->with('error', '场所删除失败');
		}
	}

	// 获取产场所信息
	public function getSiteinfo()
	{	
		$site_id = request()->get('site_id');
		$curl = new Curl();
		$curl->get('https://exadc.exands.com/api/v1.0/baseinfo/?method=getSiteInfo&site_id='.$site_id);
		if ($curl->response->errcode == 100) {
			$data['name'] = '没有数据';
			return response()->json($data);
		} else {
			return response()->json($curl->response->data->site);
		}
	}

	public function stats()
	{
		$stats = DB::select('select date, count(*) as sites from site_stats group by date order by date desc');
		$site_data = \DB::table('site_stats')
							->select('site_id', 'date')
							->orderBy('date', 'desc')
							->get();

		$data = $this->toArray($site_data);
		$summary = collect($data)->groupBy('site_id');
		$summary_data = $this->toArray($summary);

		return View('sites.summary', compact('stats', 'summary_data'));
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

	// 场所统计
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

		$stats = DB::table('site_stats')
						->leftJoin('site', 'site_stats.site_id', '=', 'site.site_id')
						->whereRaw($cond)
						->select('site.site_name', 'site_stats.clients', 'site_stats.site_id', 'site_stats.login_rcvd', 'site_stats.logout_rcvd', 'site_stats.vid_rcvd', 'site_stats.data_rcvd', 'site_stats.conn_rcvd', 'site_stats.wls_rcvd', 'site_stats.login_unknown_rcvd', 'site_stats.login_query_rcvd', 'site_stats.login_udp_rcvd', 'site_stats.login_portal_rcvd', 'site_stats.login_radius_auth_rcvd', 'site_stats.login_radius_acct_rcvd', 'site_stats.login_vendor_rcvd', 'site_stats.login_center_rcvd', 'site_stats.login_cached_rcvd', 'site_stats.login_sent', 'site_stats.logout_sent', 'site_stats.vid_sent', 'site_stats.data_sent', 'site_stats.conn_sent', 'site_stats.wls_sent', 'site_stats.date')
						->orderBy('site_stats.date', 'desc')
						->get();

		return View('sites.stats', compact('data', 'stats'));
	}

	public function logs()
	{	
		$data = request()->all();
		if (empty($data))
			return View('sites.logs');

		$date = $data['date'];
		$site_id = $data['site_id'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		$cond = 'site_id = "' . $site_id . '"';

		$tbname = 'sitelog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('sites.logs', compact('data'));

		$logs = DB::table($tbname)->whereRaw($cond)->orderBy('id')->paginate(20);
		return View('sites.logs', compact('data', 'logs'));
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

	// 场所状态导出
	public function siteStatsExcel()
	{
		$data = request()->all();
		if (empty($data))
			return View('sites.home');

		$cond = 'true';
		if (isset($data))
		{	
			$site_id = $data['site_id'];
			if (!empty($site_id))
				$cond .= ' and site.site_id = "' . $site_id . '"';
			if (!empty($data['site_name'])) {
				$cond .= ' and site_name like "%' . $data['site_name'] . '%"';
			}
			if (!empty($data['device_id'])) {
				$cond .= ' and device_id = "' . $data['device_id'] . '"';
			}
			if (!empty($data['ip_address'])) {
				$cond .= ' and site.ip_address = "' . $data['ip_address'] . '"';
			}
		}

		$sites = \DB::table('site_device')
						->leftJoin('site', 'site.site_id', '=', 'site_device.site_id')
						->select('site.site_id', 'site.site_name', 'site_device.device_id', 'site.ip_address', 'site_device.version', 'site.online_users', 'site.update_time', 'site.last_user_time', 'site.last_vid_time', 'site.last_data_time', 'site.last_conn_time', 'site.last_wls_time')
						->whereRaw($cond)
						->get();

		$cellData = [ 0 =>
			['场所号', '场所名称', '审计设备', '公网IP', '软件版本', '在线终端数', '更新时间', '最后获取实名时间', '最后获取虚拟身份时间', '最后获取上网数据时间', '最后获取上网日志时间', '最后获取感知数据时间']
			];
		$cellData = array_merge($cellData, $this->toArray($sites));
		$time = date('Y-m-d H:i:s', time());
        Excel::create('场所状态'.$time,function ($excel) use ($cellData){
            $excel->sheet('score', function ($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
	}

	// 场所批量删除
	public function siteBatchDelete($id)
	{	
		$site_id = explode(',' , $id);
    	$data = \DB::table('site')
            ->whereIn('site_id', $site_id)
            ->delete();

		$data_device = \DB::table('site_device')
							->whereIn('site_id', $site_id)
							->delete();

		if ($data) {
	        return redirect()->back()->with('success', '批量删除成功');
	    } else {
	        return redirect()->back()->with('error', '批量删除失败');
	    } 
	}
}
