<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // 后台首页
    public function index()
    {	
        // $siteData = \App\SiteIndex::all()->toArray();

        // $site_data = array();
        // foreach ($siteData as $vo) {
        //     $site_data[$vo['name']][] = $vo['keyword'];
        // }

        // $site = array();
        // foreach ($site_data as $key => $vo) {
        //     foreach ($vo as $v) {
        //         $cond = true;
        //         $cond = $cond . " and site_id like '". $v. "%'";
        //         $cond = $cond . " and ip_address = '". $v. "'";
        //         $cond = $cond . " and site_name like '%". $v. "%'";
        //         $mac = $v;
        //         // 检查是否是mac
        //         $mac = strtolower($mac);
        //         $mac = str_replace(":", "", $mac);
        //         $mac = str_replace("-", "", $mac);
        //         if (preg_match('/^[0-9a-f]{12}$/', $mac))
        //         {
        //             $cond = $cond . " and site_id in (select site_id from site_device where device_id='". $mac. "')";
        //         }
        //         $site[$key]['site'] = \App\Site::whereRaw($cond)->get();
        //     }
        // }
        
        // $siteStatus = array();
        // foreach ($site as $key => $vo) {
        //     $siteStatus[$key]['total'] = count($vo['site']);
        //     $siteStatus[$key]['online'] = '';
        //     $siteStatus[$key]['offline'] = '';
        //     foreach ($vo['site'] as $v) {
        //         if (toArray($v)['update_time'] + 600 > time()) {
        //             $siteStatus[$key]['online'][] = $v->count();
        //         } else {
        //             $siteStatus[$key]['offline'][] = $v->count();
        //         }
        //     }
        // }

        // $siteTotal = \App\Site::all()->count();

        // $site_count = array();
        // $site_count['online'] = '';
        // $site_count['offline'] = '';
        // foreach (\App\Site::all() as $vo) {
        //     if (toArray($vo)['update_time'] + 600 > time()) {
        //         $site_count['online'] = $vo->count();
        //     } else {
        //         $site_count['offline'] = $vo->count();
        //     }
        // }

        // return View('index', compact('siteStatus', 'siteTotal', 'site_count'));
        
        return View('index');
    }
}
