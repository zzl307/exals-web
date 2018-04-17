<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckController extends Controller
{
    // 查询
    public function check()
    {
        $data = request()->all();
        if (empty($data['device_id']) || empty($data['client'])) {
            request()->session()->flash('error', '缺少参数');
        }
        $date = date('Y-m-d', time());
        $table = 'userlog'.'_'.str_replace('-', '', $date);
        $tables = \DB::select('SHOW TABLES');
        $tabledata = array();
        $hour = date('Y-m-d H:i:s', time()-3600);
        $now = date('Y-m-d H:i:s', time());
        foreach ($tables as $vo) {
            $tabledata[] = $vo->Tables_in_exals;
        }
        if (in_array($table, $tabledata)) {
            $logs = \DB::table($table)
                            ->whereRaw('device_id = ? and mac = ? and event = ?', [
                                $data['device_id'], $data['client'], 'login'
                            ])
                            ->whereBetween('time', [$hour, $now])
                            ->orderBy('time', 'acs')
                            ->get();

            if ($logs->isEmpty()) {
                request()->session()->flash('error', '没有数据');
            }

            $userLog = array();
            foreach ($logs as $vo) {
                $userLog[$vo->user_id] = $vo;
            }
        }

        return View('check', compact('userLog', 'data'));
    }
}
