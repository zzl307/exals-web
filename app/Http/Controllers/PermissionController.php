<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // 权限管理
    // 权限列表
    public function permission()
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

            \App\AdminPermission::create(request(['name', 'description']));
            return redirect('user/permission')->with('success', '操作成功');
        }
        $permissions = \App\AdminPermission::all();

        return View('permission.permission', compact('permissions'));
    }

    // 删除权限
    public function permission_delete($id)
    {
        $data = \App\AdminPermission::find($id);

        if ($data->delete()) {
            return redirect('user/permission')->with('success', '删除成功');
        } else {
            return redirect()->back()->with('error', '删除失败');
        }
    }
}
