<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    // 系统设置
    public function systemConfig()
    {
    	return View('system.systemConfig');
    }
}
