<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MacMonitor;

class MacMonitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function main()
	{	
		return View('mac_monitor.home');
	}

	public function add()
	{
		if (!request()->isMethod('POST'))
			return View('mac_monitor.add');
		
		$data = request()->input('Monitor');

		$mac = strtolower($data['mac']);
		$mac = str_replace(":", "", $mac);
		$mac = str_replace("-", "", $mac);

		if (!preg_match('/^[0-9a-f]{12}$/', $mac))
		{
			$errmsg = 'MAC地址格式错误';
			return View('mac_monitor.add', compact('data', 'errmsg'));
		}

		$user = MacMonitor::find($mac);
		if ($user)
		{
			$errmsg = 'MAC已经存在';
			return View('mac_monitor.add', compact('data', 'errmsg'));
		}

		$user = new MacMonitor;
		$user->mac = $mac;

		if (!$user->save())
		{
			$errmsg = '数据库操作失败';
			return View('mac_monitor.add', compact('data', 'errmsg'));
		}

		return View('mac_monitor.home');
	}

	public function delete($mac)
	{
		$user = MacMonitor::find($mac);
		if (!isset($user))
			return redirect()->back()->with('error', '用户信息不存在');

		if (!$user->delete())
			return redirect()->back()->with('error', '删除失败');

		return redirect()->back()->with('success', '删除成功');
	}
}
