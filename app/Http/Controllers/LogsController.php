<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients;
use Illuminate\Support\Facades\DB;
use Excel;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function userlogs()
	{	
		$data = request()->all();
		if (empty($data))
			return View('logs.userlogs');

		$date = $data['date'];
		$site_id = $data['site_id'];
		$mac = $data['mac'];
		$user_id = $data['user_id'];
		$id_type = $data['id_type'];
		$vid = $data['vid'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		if (!empty($mac))
		{
			// 检查是否是mac
			$mac = strtolower($mac);
			$mac = str_replace(":", "", $mac);
			$mac = str_replace("-", "", $mac);
 
			if (!preg_match('/^[0-9a-f]{12}$/', $mac))
			{
				$errmsg = 'MAC地址格式错误';
				return View('logs.userlogs', compact('data', 'errmsg'));
			}
			$data['mac'] = $mac;
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "' . $site_id . '"';

		if (!empty($mac))
			$cond .= ' and mac = "' . $mac . '"';

		if (!empty($user_id))
			$cond .= ' and user_id = "' . $user_id. '"';

		if (!empty($id_type))
			$cond .= ' and id_type = "' . $id_type. '"';

		if ($vid != -1) {
			$cond .= ' and vid = "' . $vid. '"';
		}

		$tbname = 'userlog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('logs.userlogs', compact('data'));

		$logs = DB::table($tbname)
						->whereRaw($cond)
						->orderBy('id', 'desc')
						->paginate(20);

		return View('logs.userlogs', compact('data', 'logs'));
	}

	// 虚拟身份统计
	public function idTypeCount()
	{
		$date = request()->input('date');
		$vid = request()->input('vid');
		$dateCount = $this->getDateCount($date);
		$logs = array();
		foreach ($dateCount as $vo) {
			$tbname = 'userlog'.'_'.str_replace('-', '', $vo);
			$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
			if (count($tables) == 0) {
				$idTypeCount = '没有数据';
				return json_encode($idTypeCount);
			}

			$logs[$vo] = DB::table($tbname)
									->select(\DB::raw('count(*) as id_type_count, id_type'))
									->where('vid', '=', $vid)
									->groupBy('id_type')
									->orderBy('id_type_count', 'desc')
									->get();
		}

		$date_count = array();
		foreach (toArray($logs) as $key => $vo) {
			foreach ($vo as $id_type) {
				$date_count[$id_type['id_type']][$key] = $id_type['id_type_count'];
			}
		}

		$dateCountData = array();
		foreach ($dateCount as $vo) {
			foreach ($date_count as $key => $count) {
				$dateCountData[$key][$vo] = array_get($count, $vo, 0);
			}
		}

		$idTypeCount['thead'] = '';
		$idTypeCount['tbody'] = '';
		$idTypeCount['thead'] .= '<tr>';
		$idTypeCount['thead'] .= '<th>类型</th>';
		foreach ($dateCount as $vo) {
			$idTypeCount['thead'] .= '<th>'.$vo.'</th>';
		}
		$idTypeCount['thead'] .= '</tr>';
		foreach ($dateCountData as $key => $vo) {
			$idTypeCount['tbody'] .= '<tr>';
			$idTypeCount['tbody'] .= '<td><code>'.$key.'</code></td>';
			foreach ($vo as $id_type) {
				$idTypeCount['tbody'] .= '<td>'.$id_type.'</td>';
			}
			$idTypeCount['tbody'] .= '</tr>';
		}

		return json_encode($idTypeCount);
	}

	public function getDateCount($date)
	{	
		$dateCount = array();
		for ($i=0; $i<= 7; $i++) { 
			$dateCount[] = date('Y-m-d', strtotime($date.'-'.$i.'day'));		
		}

		return $dateCount;
	}

	public function httplogs()
	{	
		$data = request()->all();
		if (empty($data))
			return View('logs.httplogs');

		$date = $data['date'];
		$site_id = $data['site_id'];
		$mac = $data['mac'];
		if (!isset($data['type'])) {
			$data['type'] = '';
		}
		if (!isset($data['field'])) {
			$data['field'] = '';
		}
		if (!isset($data['keyword'])) {
			$data['keyword'] = '';
		}
		if (array_key_exists('type', $data))
			$type = $data['type'];
		if (array_key_exists('field', $data))
			$field = $data['field'];
		if (array_key_exists('keyword', $data))
			$keyword = $data['keyword'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		if (!empty($mac))
		{
			// 检查是否是mac
			$mac = strtolower($mac);
			$mac = str_replace(":", "", $mac);
			$mac = str_replace("-", "", $mac);
 
			if (!preg_match('/^[0-9a-f]{12}$/', $mac))
			{
				$errmsg = 'MAC地址格式错误';
				return View('logs.httplogs', compact('data', 'errmsg'));
			}
			$data['mac'] = $mac;
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "'.$site_id.'"';

		if (!empty($mac))
			$cond .= ' and mac = "'.$mac.'"';

		if (!empty($type))
			$cond .= ' and type = "'.$type.'"';

		if (!empty($field) && !empty($keyword))
			$cond .= ' and url regexp "\"'.$field.'\":\"[^\"]*'.$keyword.'[^\"]*"';

		$tbname = 'httplog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('logs.httplogs', compact('data'));

		$logs = DB::table($tbname)->whereRaw($cond)->orderBy('id', 'desc')->paginate(20);

		if ($logs->isEmpty()) {
			$logs = '';
		}

		return View('logs.httplogs', compact('data', 'logs'));
	}

	// 上网数据导出
	public function export()
	{
		$data = request()->all();
		if (empty($data))
			return View('logs.httplogs');

		$date = $data['date'];
		$site_id = $data['site_id'];
		$mac = $data['mac'];
		if (array_key_exists('type', $data))
			$type = $data['type'];
		if (array_key_exists('field', $data))
			$field = $data['field'];
		if (array_key_exists('keyword', $data))
			$keyword = $data['keyword'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		if (!empty($mac))
		{
			// 检查是否是mac
			$mac = strtolower($mac);
			$mac = str_replace(":", "", $mac);
			$mac = str_replace("-", "", $mac);
 
			if (!preg_match('/^[0-9a-f]{12}$/', $mac))
			{
				$errmsg = 'MAC地址格式错误';
				return View('logs.httplogs', compact('data', 'errmsg'));
			}
			$data['mac'] = $mac;
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "'.$site_id.'"';

		if (!empty($mac))
			$cond .= ' and mac = "'.$mac.'"';

		if (!empty($type))
			$cond .= ' and type = "'.$type.'"';

		if (!empty($field) && !empty($keyword))
			$cond .= ' and url regexp "\"'.$field.'\":\"[^\"]*'.$keyword.'[^\"]*"';

		$tbname = 'httplog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('logs.httplogs', compact('data'));

		$logs = DB::table($tbname)
						->whereRaw($cond)
						->orderBy('id', 'desc')
						->get();

		$content = [ 0 =>
			['时间', '场所号', '终端MAC', '本地地址:端口', '服务器地址:端口', '类型', '内容']
			];

		foreach ($logs as $vo)
		{
			$a = array();
			$a[] = $vo->time;
			$a[] = $vo->site_id;
			$a[] = $vo->mac;
			$a[] = $vo->local_ip.':'.$vo->local_port;
			$a[] = $vo->remote_ip.':'.$vo->remote_port;
			$a[] = $vo->type;
			$a[] = $vo->url;
			$content[] = $a;
		}

        Excel::create('上网数据-'.date('YmdHis', time()), function ($excel) use ($content) {
	            $excel->sheet('score', function ($sheet) use ($content) { $sheet->rows($content);
            });
        })->export('xls');
	}

	public function connlogs()
	{	
		$data = request()->all();
		if (empty($data))
			return View('logs.connlogs');

		$date = $data['date'];
		$site_id = $data['site_id'];
		$mac = $data['mac'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		if (!empty($mac))
		{
			// 检查是否是mac
			$mac = strtolower($mac);
			$mac = str_replace(":", "", $mac);
			$mac = str_replace("-", "", $mac);
 
			if (!preg_match('/^[0-9a-f]{12}$/', $mac))
			{
				$errmsg = 'MAC地址格式错误';
				return View('logs.connlogs', compact('data', 'errmsg'));
			}
			$data['mac'] = $mac;
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "' . $site_id . '"';

		if (!empty($mac))
			$cond .= ' and mac = "' . $mac . '"';

		$tbname = 'connlog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('logs.connlogs', compact('data'));

		$logs = DB::table($tbname)->whereRaw($cond)->orderBy('id', 'desc')->paginate(20);
		return View('logs.connlogs', compact('data', 'logs'));
	}

	public function wlslogs()
	{	
		$data = request()->all();
		if (empty($data))
			return View('logs.wlslogs');

		$date = $data['date'];
		$site_id = $data['site_id'];
		$epmac = $data['epmac'];
		$apmac = $data['apmac'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		if (!empty($epmac))
		{
			// 检查是否是epmac
			$epmac = strtolower($epmac);
			$epmac = str_replace(":", "", $epmac);
			$epmac = str_replace("-", "", $epmac);
 
			if (!preg_match('/^[0-9a-f]{12}$/', $epmac))
			{
				$errmsg = '终端MAC地址格式错误';
				return View('logs.wlslogs', compact('data', 'errmsg'));
			}
			$data['epmac'] = $epmac;
		}

		if (!empty($apmac))
		{
			// 检查是否是apmac
			$apmac = strtolower($apmac);
			$apmac = str_replace(":", "", $apmac);
			$apmac = str_replace("-", "", $apmac);
 
			if (!preg_match('/^[0-9a-f]{12}$/', $apmac))
			{
				$errmsg = 'AP MAC地址格式错误';
				return View('logs.wlslogs', compact('data', 'errmsg'));
			}
			$data['apmac'] = $apmac;
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "' . $site_id . '"';

		if (!empty($epmac))
			$cond .= ' and ep_mac = "' . $epmac . '"';

		if (!empty($apmac))
			$cond .= ' and ap_mac = "' . $apmac . '"';

		$tbname = 'wlslog'.'_'.str_replace('-', '', $data['date']);
		$tables = DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('logs.wlslogs', compact('data'));

		$logs = DB::table($tbname)->whereRaw($cond)->orderBy('id', 'desc')->paginate(20);
		return View('logs.wlslogs', compact('data', 'logs'));
	}
}
