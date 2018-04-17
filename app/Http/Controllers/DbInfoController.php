<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DbInfoController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getTableAllStats($tbname)
	{
		$stats = array();
		$stats['total'] = 0;
		foreach (DB::select("SELECT DATA_LENGTH+INDEX_LENGTH as data_bytes, table_name FROM information_schema.TABLES where TABLE_SCHEMA='exals' AND table_name LIKE '".$tbname."_%' ORDER BY table_name DESC") as $vo)
		{
			$a = array();
			$a['tbname'] = $vo->table_name;
			$a['bytes'] = $vo->data_bytes;

			$stats['total'] += $vo->data_bytes;
			$stats['tables'][] = $a;
		}

		return $stats;
	}

	public function getTableStats($tbname)
	{
		$stats = array();
		$stats['total'] = 0;
		foreach (DB::select("SELECT DATA_LENGTH+INDEX_LENGTH as data_bytes, table_name FROM information_schema.TABLES where TABLE_SCHEMA='exals' AND table_name LIKE '".$tbname."_%' ORDER BY table_name DESC") as $vo)
		{
			$row = DB::table($vo->table_name)->count();
			$a = array();
			$a['tbname'] = $vo->table_name;
			$a['bytes'] = $vo->data_bytes;
			$a['records'] = $row;

			$stats['total'] += $vo->data_bytes;
			$stats['tables'][] = $a;
		}

		return $stats;
	}

	public function showDbInfo()
	{
		$result = DB::select("SELECT SUM(DATA_LENGTH+INDEX_LENGTH) as total FROM information_schema.TABLES where TABLE_SCHEMA='exals'");
		$total = $result[0]->total;

		$tables = array();
		$tables['clientlog'] = $this->getTableAllStats('clientlog');
		$tables['sitelog'] = $this->getTableAllStats('sitelog');
		$tables['userlog'] = $this->getTableAllStats('userlog');
		$tables['httplog'] = $this->getTableAllStats('httplog');
		$tables['connlog'] = $this->getTableAllStats('connlog');
		$tables['wlslog'] = $this->getTableAllStats('wlslog');
		$tables['paibo'] = $this->getTableAllStats('paibo');

		$date = \DB::table('config_params')
						->get();				

		$tables['clientlog']['date'] = isset(head(toArray($date))['clientlog']) ? head(toArray($date))['clientlog'] : '30';
		$tables['sitelog']['date'] = isset(head(toArray($date))['sitelog']) ? head(toArray($date))['sitelog'] : '7';
		$tables['userlog']['date'] = isset(head(toArray($date))['userlog']) ? head(toArray($date))['userlog'] : '7';
		$tables['httplog']['date'] = isset(head(toArray($date))['httplog']) ? head(toArray($date))['httplog'] : '3';
		$tables['connlog']['date'] = isset(head(toArray($date))['connlog']) ? head(toArray($date))['connlog'] : '3';
		$tables['wlslog']['date'] = isset(head(toArray($date))['wlslog']) ? head(toArray($date))['wlslog'] : '3';
		$tables['paibo']['date'] = isset(head(toArray($date))['paibo']) ? head(toArray($date))['paibo'] : '7';

		return View('dbinfo.stats', compact('total', 'tables'));
	}

	// 修改数据库保存时间
	public function getDbDate ()
	{
		if (request()->isMethod('POST')) {
			$name = substr(request()->input('name'), 3);
			$date = request()->input('date');
			$data = \DB::table('config_params')
							->update([
								$name => $date,
								'update_time' => date('Y-m-d H:i:s', time())
							]);

			if ($data) {
				return $date;
			}	
		}
	}

	// 数据库概况
	public function getDbInfo()
	{
		$name = request()->input('name');
		$table = $this->getTableStats($name);
		
		if (isset($table['tables'])) {
			return json_encode($table['tables']);
		}
	}

	// 删除数据表
	public function deleteDbInfo()
	{
		$tbname = request()->input('tbname');
		$tb = \Schema::drop($tbname);
		if (empty($tb)) {
			return redirect()->back()->with('success', '删除成功 '.$tbname);
		} else {
			return redirect()->back()->with('error', '删除失败 '.$tbname);
		}
	}
}
