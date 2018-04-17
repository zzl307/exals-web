<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteIndex extends Model
{
    // 设置模型关联表
	protected $table = 'site_index';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'id';

	protected $fillable = ['name', 'keyword'];
}
