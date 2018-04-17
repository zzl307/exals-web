<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientAlert extends Model
{
    // 设置模型关联表
	protected $table = 'client_alert';

	// 自动维护时间戳
	public $timestamps = false;

	// 设置主键
	protected $primaryKey = 'user';
	protected $keyType = 'string';
}
