<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    // 设置模型关联表
	protected $table = 'sites';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'site_id';
	protected $keyType = 'string';
}
