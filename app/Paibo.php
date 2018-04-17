<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paibo extends Model
{
	// 设置模型关联表
	protected $table = 'paibo';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置时间戳
	protected function getDateFormat(){
		return time();
	}
}
