<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paibo;

class PaiboController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function main()
	{
		return View('paibo.home');
	}

	public function search()
	{
		$data = request()->all();
		$date = $data['date'];
		$site_id = isset($data['site_id']) ? $data['site_id'] : '';
		$user_id = isset($data['user_id']) ? $data['user_id'] : '';
		$mac = isset($data['mac']) ? $data['mac'] : '';

		if (empty($site_id) && empty($user_id) && empty($mac)) {
			session()->flash('error', '场所号，用户身份，MAC不能同时为空');

			return View('paibo.home', compact('data'));
		}

		if (empty($date)) {
			$date = date('Y-m-d', time());
			$data['end_time'] = $date;
		}

		$cond = 'true';

		if (!empty($site_id)) {
			$cond .= ' and site_id = "' . $site_id . '"';
		}

		if (!empty($user_id)) {
			$cond .= ' and user_id = "' . $user_id . '"';
		}

		if (!empty($mac)) {
			// 检查是否是mac
			$mac = strtolower($mac);
			$mac = str_replace(":", "", $mac);
			$mac = str_replace("-", "", $mac);

			if (!preg_match('/^[0-9a-f]{12}$/', $mac)) {
				$errmsg = 'MAC地址格式错误';

				return View('paibo.home', compact('data', 'errmsg'));
			}

			$cond .= ' and mac = "' . $mac . '"';
		}

		$cond .= ' and date(time) = "' . $date . '"';

		$table = 'paibo'.'_'.str_replace('-', '', $data['date']);
		$tables = \DB::select('SHOW TABLES');
		$tabledata = array();
		foreach ($tables as $vo) {
			$tabledata[] = $vo->Tables_in_exals;
		}
		if (in_array($table, $tabledata)) {
			$logs = \DB::table($table)->whereRaw($cond)->orderBy('id', 'desc')->paginate(15);
		} else {
			$logs = '';
			session()->flash('warning', '没有数据');
		}

		return View('paibo.home', compact('data', 'logs'));
	}

	public function log($id, $date)
	{	
		$table = 'paibo'.'_'.str_replace('-', '', $date);
		$tables = \DB::select('SHOW TABLES');
		$tabledata = array();
		foreach ($tables as $vo) {
			$tabledata[] = $vo->Tables_in_exals;
		}
		if (in_array($table, $tabledata)) {
			$log = \DB::table($table)
							->where('id', '=', $id)
							->first();
		} else {
			return redirect()->back()->with('error', '记录未找到');
		}

		return View('paibo.log', compact('log'));
	}
}
