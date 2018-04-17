<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\ClientAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function home(Request $request)
	{	
		$request->flash();

		$persist = $request->input('persist');
		if (!empty($persist) && $persist != 0)
		{
			$clients = Client::getPersistClients();
			return View('clients.home', compact('clients'));
		}

		$keyword = $request->input('keyword');
		if (empty($keyword))
			return View('clients.home');

		// 检查是否是mac
		$mac = strtolower($keyword);
		$mac = str_replace(":", "", $mac);
		$mac = str_replace("-", "", $mac);
		if (preg_match('/^[0-9a-f]{12}$/', $mac))
		{
			$clients = Client::getClientsByMac($mac);
		}
		else
		{
			$clients = Client::getClientsByUserId($keyword);
		}

		return View('clients.home', compact('clients'));
	}

	public function showAddClientForm(Request $request)
	{
		if ($request->has('update'))
			$request->flash();

		$origurl = $request->input('origurl');
		if ($origurl == null)
			$origurl = $request->headers->get('referer');

		return View('clients.add', compact('origurl'));
	}

	public function addClient(Request $request)
	{
		$this->validate($request, [
			'client_mac' => 'required|mac',
			'user_id' => 'required|string|max:32',
			'id_type' => 'required|string|max:32',
		]);

		$request->flash();
		$update = $request->has('update');

		$mac = $request->input('client_mac');
		$mac = strtolower($mac);
		$mac = str_replace(":", "", $mac);
		$mac = str_replace("-", "", $mac);
		$client_mac = $mac;

		$user = Client::find($client_mac);
		if ($user && !$update)
		{
			$errors = ['client_mac' => 'MAC已经存在'];
			return redirect()->back()->withErrors($errors);
		}

		if ($user == null)
			$user = new Client;

		$user->mac = $client_mac;
		$user->user_id = $request->input('user_id');
		$user->id_type = $request->input('id_type');
		$user->persist = $request->has('persist');

		$user->save();

		if ($request->has('alert'))
			return redirect('client/alert/add?user='.$client_mac.','.$request->input('user_id'));

		if ($update)
			return redirect($request->input('origurl'));

		return redirect('client/list?keyword='.$mac);
	}

	public function delClient(Request $request)
	{
		if ($request->has('client_mac'))
			Client::destroy($request->input('client_mac'));

		return redirect()->back();
	}

	public function alert(Request $request)
	{
		if($request->has('user'))
		{
			$users = explode(',', $request->input('user'));
			$alerts = ClientAlert::whereIn('user', $users)->paginate(15);
		}
		else
			$alerts = ClientAlert::orderBy('time', 'desc')->paginate(15);

		return View('clients.alert', compact('alerts'));
	}

	public function showAddAlertForm(Request $request)
	{
		if ($request->has('update'))
			$request->flash();

		$origurl = $request->input('origurl');
		if ($origurl == null)
			$origurl = $request->headers->get('referer');

		return View('clients.add_alert', compact('origurl'));
	}

	public function addAlert(Request $request)
	{
		$validates = [
			'user' => 'required|string|max:100',
			'reason' => 'required|string|max:255',
		];
		if ($request->has('expiry'))
			$validates = array_merge($validates, [ 'expiry' => 'required|date' ]);

		$this->validate($request, $validates);

		$users = explode(',', $request->input('user'));

		foreach ($users as $user)
		{
			$user = trim($user);
			$alert = ClientAlert::findOrNew($user);
			$alert->time = date('Y-m-d H:i:s', time());
			$alert->user = $user;
			$alert->reason = $request->input('reason');
			if ($request->has('expiry'))
				$alert->expiry = $request->input('expiry');
			$alert->data = $request->has('data');

			$alert->save();
		}

		return redirect('client/alert');
	}

	public function delAlert(Request $request)
	{
		if ($request->has('user'))
			ClientAlert::destroy($request->input('user'));

		return redirect()->back();
	}

	// 报警记录
	public function alarm()
	{	
		$data = request()->all();

		$cond = 'true';
		if (!empty($data['user'])) {
			$cond .= ' and mac = "' . $data['user'] . '"';
		}

		$alarm = \DB::table('client_alarm')
						->leftJoin('client_alert', 'client_alarm.alert_id', '=', 'client_alert.id')
						->whereRaw($cond)
						->select('client_alert.user', 'client_alert.reason', 'client_alarm.mac', 'client_alarm.id_type', 'client_alarm.site_id', 'client_alarm.time', 'client_alarm.user_id')
						->orderBy('client_alarm.time', 'desc')
						->paginate(15);

		return View('clients.alarm', compact('data', 'alarm'));
	}

	public function logs()
	{	
		$data = request()->all();

		if (empty($data))
			return View('clients.logs');

		$date = $data['date'];
		$site_id = $data['site_id'];
		$mac = $data['mac'];
		$user_id = $data['user_id'];
		$action = $data['action'];

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
				return View('clients.logs', compact('data', 'errmsg'));
			}
			$data['mac'] = $mac;
		}

		$cond = 'true';

		if (!empty($site_id))
			$cond .= ' and site_id = "' . $site_id . '"';

		if (!empty($mac))
			$cond .= ' and mac = "' . $mac . '"';

		if (!empty($user_id))
			$cond .= ' and user_id = "' . $user_id . '"';

		if (isset($action) && $action >= 0)
			$cond .= ' and action = ' . $action;

		$tbname = 'clientlog'.'_'.str_replace('-', '', $data['date']);
		$tables = \DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('clients.logs', compact('data'));

		$logs = \DB::table($tbname)->whereRaw($cond)->orderBy('time', 'desc')->paginate(20);
		
		return View('clients.logs', compact('data', 'logs'));
	}

	// 终端频次
	public function screenshot()
	{	
		$data = request()->all();

		if (empty($data))
			return View('clients.screenshot');

		$date = $data['date'];
		$action = $data['action'];

		if (empty($date))
		{
			$date = date('Y-m-d', time());
			$data['date'] = $date;
		}

		$tbname = 'clientlog'.'_'.str_replace('-', '', $data['date']);
		$tables = \DB::select("SHOW TABLES LIKE '".$tbname."'");
		if (count($tables) == 0)
			return View('clients.screenshot', compact('data'));

		$screenshot = \DB::select('select mac, count from (select mac, count(distinct(site_id)) as count from '.$tbname.' group by mac) as stats where count > '.$action.' order by count desc');

		$screenshot_data = toArray($screenshot);

		$perPage = 16;
        if (request()->has('page')) {
            $current_page = request()->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }
        $item = array_slice($screenshot_data, ($current_page-1)*$perPage, $perPage);
        $total = count($screenshot_data);
        $paginator = new LengthAwarePaginator($item, $total, $perPage, $currentPage = '', [
          'path' => Paginator::resolveCurrentPath(),
          'pageName' => 'page',
        ]);

        $screenshotData = $paginator->toArray()['data'];

		return View('clients/screenshot', compact('screenshotData', 'paginator', 'data'));
	}

	public function macSite()
	{
		$data = request()->all();

		$tbname = 'clientlog'.'_'.str_replace('-', '', $data['date']);
		$site = \DB::table($tbname)
						->where('mac', '=', $data['mac'])
						->get();

		$macSite = toArray(collect($site)->sortBy('time'));
		$site_data = array();

		foreach (toArray(collect($macSite)->groupBy('site_id')) as $key => $vo) {
			$site_data[$key] = collect($vo)->first();
		}

		$site_mac_data = '';
		foreach ($site_data as $vo) {
			$site_mac_data .= '<tr>';
			$site_mac_data .= '<td>'.$vo['time'].'</td>';
			if ($vo['action'] == 0) {
				$site_mac_data .= '<td>查询</td>';
			} elseif ($vo['action'] == 1) {
				$site_mac_data .= '<td><span class="label label-success">登录</span></td>';
			} elseif ($vo['action'] == 2) {
				$site_mac_data .= '<td><span class="label label-default">登出</span></td>';
			} elseif ($vo['action'] == 100) {
				$site_mac_data .= '<td><span class="label label-warning">出现</span></td>';
			}
			$site_mac_data .= '<td>'.$vo['device_id'].'</td>';
			$site_mac_data .= '<td>'.$vo['site_id'].'</td>';
			$site_mac_data .= '<td>'.\App\Site::getSiteName($vo['site_id']).'</td>';
			$site_mac_data .= '<td>'.$vo['mac'].'</td>';
			$site_mac_data .= '<td>'.$vo['local_ip'].'</td>';
			$site_mac_data .= '<td>'.$vo['user_id'].'</td>';
			$site_mac_data .= '<td>'.$vo['id_type'].'</td>';
			if ($vo['action'] == 1) {
				if ($vo['method'] == 1) {
					$site_mac_data .= '<td>反查</td>';
				} elseif ($vo['method'] == 2) {
					$site_mac_data .= '<td>消息</td>';
				} elseif ($vo['method'] == 3) {
					$site_mac_data .= '<td>Portal截取</td>';
				} elseif ($vo['method'] == 4) {
					$site_mac_data .= '<td>Radius认证</td>';
				} elseif ($vo['method'] == 5) {
					$site_mac_data .= '<td>第三方</td>';
				} elseif ($vo['method'] == 6) {
					$site_mac_data .= '<td>中心反查</td>';
				} elseif ($vo['method'] == 7) {
					$site_mac_data .= '<td>Radius计费</td>';
				} elseif ($vo['method'] == 8) {
					$site_mac_data .= '<td>缓存</td>';
				} else {
					$site_mac_data .= '<td>未知</td>';
				}
			} else {
				$site_mac_data .= '<td></td>';
			}

			$site_mac_data .= '</tr>';
		}

		return json_encode($site_mac_data);
	}
}
