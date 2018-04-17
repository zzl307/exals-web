<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    // 设置模型关联表
    protected $table = 'admin_permission';

    // 批量赋值字段
    protected $fillable = ['keyword', 'name', 'description'];

    // 权限属性哪个角色
    public function roles()
    {
        return $this->belongsToMany(\App\AdminRole::class, 'admin_role_permission', 'permission_id', 'role_id')
                    ->withPivot([
                        'permission_id',
                        'role_id'
                    ]);
    }
}
