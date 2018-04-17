<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    // 角色管理
    // 角色列表
    public function roles()
    {   
        if (request()->isMethod('POST')) {
            $validator = \Validator::make(request()->input(), [
                'name' => 'required|min:3',
                'description' => 'required',
            ],[
                // 定义错误类型
                'required' => ':attribute 为必填项',
                'min' => ':attribute 最小3个字符'
            ],[
                // 定义验证字段
                'name' => '角色名称',
                'description' => '描述'
            ]);
            // 判断有没有错误
            if ($validator->fails()) {
                // 手动注册错误提示 数据保持
                return redirect()->back()->withErrors($validator)->withInput();
            }

            \App\AdminRole::create(request(['name', 'description']));
            return redirect('user/roles')->with('success', '操作成功');
        }
        $roles = \App\AdminRole::all();

        return View('role.role', compact('roles'));
    }

    // 角色和权限的关系
    public function permission(\App\AdminRole $role)
    {   
        if (request()->isMethod('POST')) {
            $this->validate(request(),[
               'permissions' => 'required|array'
            ]);

            $permissions = \App\AdminPermission::find(request('permissions'));
            $myPermissions = $role->permissions;

            // 对已经有的权限
            $addPermissions = $permissions->diff($myPermissions);
            $add = array();
            foreach ($addPermissions as $permission) {
                $add = $role->grantPermission($permission);
            }
            if ($add) {
                return redirect('user/roles')->with('success', '操作成功');
            }

            $deletePermissions = $myPermissions->diff($permissions);
            $delete = array();
            foreach ($deletePermissions as $permission) {
                $delete = $role->deletePermission($permission);
            }
            if ($delete) {
                return redirect('user/roles')->with('success', '操作成功');
            }
            return back();
        }
        $permissions = \App\AdminPermission::all();
        $myPermissions = $role->permissions;
        return view('role.permission', compact('permissions', 'myPermissions', 'role'));
    }
}
