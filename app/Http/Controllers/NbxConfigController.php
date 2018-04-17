<?php

namespace App\Http\Controllers;

use App\NbxConfig;
use Illuminate\Http\Request;

class NbxConfigController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    // 数据统计
    public function index()
    {	
    	$data = request()->all();

    	if (empty($data)) {
    		return view('nbx.index', compact('data'));
    	}

    	if (empty($data['date'])) {
			$date = date('Y-m-d', time());
			$data['end_time'] = $date;
		} else {
			$date = $data['date'];
		}
		$site_id = isset($data['site_id']) ? $data['site_id'] : '';
		$user_id = isset($data['user_id']) ? $data['user_id'] : '';
		$mac = isset($data['mac']) ? $data['mac'] : '';

		if (empty($site_id) && empty($user_id) && empty($mac)) {
			session()->flash('error', '场所号，用户身份，MAC不能同时为空');

			return view('nbx.index', compact('data'));
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

				return view('nbx.index', compact('data', 'errmsg'));
			}

			$cond .= ' and mac = "' . $mac . '"';
		}

		$cond .= ' and date(time) = "' . $date . '"';

		$table = 'nuobixing'.'_'.str_replace('-', '', $data['date']);
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

    	return view('nbx.index', compact('data', 'logs'));
    }

    // 数据详情
    public function show()
    {	
    	$id = request()->input('id');
    	$date = request()->input('date');
    	$table = 'nuobixing'.'_'.str_replace('-', '', $date);
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

		return View('nbx.show', compact('log'));
    }

    // 数据配置
    public function create(NbxConfig $nbx_config)
    {	
    	if (!empty($nbx_config->first())) {
    		$data = $nbx_config->first()->toArray();
    	} else {
    		$data = '';
    	}

    	return view('nbx.create', compact('data'));
    }

    // 修改数据配置
    public function store(NbxConfig $nbx_config)
    {	
    	if (request()->isMethod('POST')) {
    		$data = request()->all();

    		$nbx_data = $nbx_config->updateOrcreate(['id' => $data['id']], [
    			'server' => $data['server'],
    			'port' => $data['port'],
    			'domains' => str_replace('<br />', ',', trim(nl2br($data['domains']))),
    			'upload' => $data['upload']
    		]);

    		if ($nbx_data) {
    			return redirect()->back()->with('success', '操作成功');
    		}
    	}
    }
}
