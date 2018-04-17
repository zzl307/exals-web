<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function home()
	{
		return View('match.home');
	}

	public function match()
	{
		$range = request()->input('range');
		$site_id = request()->input('site_id');
		$content = request()->input('content');

		$users = array();
		$records = array();
		$lno = 0;
		foreach (explode("\n", str_replace("\r", "", $content)) as $line)
		{
			$lno++;
			$fields = explode(",", $line);

			$r = array();
			$r['site_id'] = $site_id;
			$r['time'] = array_shift($fields);
			$r['mac'] = array_shift($fields);
			$r['user_id'] = array_shift($fields);
			$r['match'] = 0;

			$mac = strtolower($r['mac']);
			$mac = str_replace(":", "", $mac);
			$mac = str_replace("-", "", $mac);
			$r['mac'] = $mac;

			if (empty($r['time']) || empty($r['mac']) || empty($r['user_id']))
			{
				$errmsg = 'line '.$lno.': '.$line.' 格式错误';
				return View('match.home', compact('site_id', 'content', 'errmsg'));
			}

			if (!isset($users[$mac]))
			{
				$user['user_id'] = $r['user_id'];
				$user['seen'] = 0;

				$users[$mac] = $user;
			}

			$records[] = $r;
		}

		$total = count($records);
		$unmatch = 0;

		$results = array();
		foreach ($records as $r)
		{
			$cond = "action = 1";
			$cond .= " and site_id = '".$r['site_id']."'";
			$cond .= " and mac = '".$r['mac']."'";
			$cond .= " and user_id = '".$r['user_id']."'";
			$cond .= " and time >= '".$r['time']."' - interval ".$range." minute";
			$cond .= " and time <= '".$r['time']."' + interval ".$range." minute";

			$r['match'] = \DB::table('clientlog')->whereRaw($cond)->count();
			if ($r['match'] > 0)
				$users[$r['mac']]['seen'] = 1;
			else
				$unmatch++;

			$results[] = $r;
		}

		$clients = count($users);
		$unseen = 0;
		foreach ($users as $u)
		{
			if ($u['seen'] == 0)
				$unseen++;
		}

		return View('match.result', compact('total', 'unmatch', 'clients', 'unseen', 'results', 'users'));
	}
}
