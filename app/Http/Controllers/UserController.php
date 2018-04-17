<?php

namespace App\Http\Controllers;

use App\AdminUser;
use App\AdminRole;
use App\AdminPermission;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mail;

class UserController extends Controller
{
	use RegistersUsers;

	public function userList()
	{
		$users = AdminUser::users();
		return View('user.users', compact('users'));
	}

	public function addUser()
	{
		if (request()->isMethod('POST'))
		{
			$name = request()->input('name');
			$email = request()->input('email');

			$user = DB::table('admin_user')->where('name', $name)->orWhere('email', $email)->count();
			if ($user > 0)
				return redirect()->back()->with('error', '用户名或者邮箱已经存在');

			$plaintxt = substr(md5(rand()), -8);

			$user = new AdminUser;
			$user->name = $name;
			$user->email = $email;
			$user->password = bcrypt($plaintxt);
			$user->save();

			$content = '用户名:'.$email.', 密码:'.$plaintxt;

			Mail::raw($content, function ($message) use ($user) {
				$message->from('noreply@exands.cn', '');
				$message->subject('exands审计数据平台账号');
				$message->to($user->email);
			});

			return redirect()->back()->with('success', '添加成功，密码已发送至用户邮箱');
		}

		return redirect()->back();
	}

	public function setUserRoles()
	{
		if (request()->isMethod('POST'))
		{
			$id = request()->input('id');
			$user = AdminUser::find($id);
			if (!$user)
				return redirect()->back()->with('error', '用户已经不存在');

			DB::table('admin_user_role')->where('user_id', $id)->delete();

			$roles = AdminRole::findMany(request('roles'));
			foreach ($roles as $role)
				$user->assignRole($role);
		}

		return redirect()->back();
	}

	// 用户密码重置
	public function resetUserPassword()
	{
		$id = request()->input('id');
		$user = AdminUser::find($id);
		if (!$user)
			return redirect()->back()->with('error', '用户已经不存在');

		$plaintxt = substr(md5(rand()), -8);

		// 密码加密
		$user->password = bcrypt($plaintxt);
		$user->save();

		try {
			Mail::raw($plaintxt, function ($message) use ($user) {
				$message->from('noreply@exands.cn', '');
				$message->subject('exands审计数据平台密码更新');
				$message->to($user->email);
			});
		} catch (\Exception $e) {
			if (!empty($e->getMessage())) {
				return redirect()->back()->with('error', '密码重置成功邮件发送失败,密码'.$plaintxt);
			}
		}

		return redirect()->back()->with('success', '密码已经发送至用户邮箱');
	}

	public function deleteUser()
	{
		$id = request()->input('id');
		$user = AdminUser::find($id);
		if (!$user)
			return redirect()->back()->with('error', '用户已经不存在');

		$user->delete();
		DB::table('admin_user_role')->where('user_id', $id)->delete();

		return redirect()->back()->with('success', '用户'.$user->name.'已被删除');
	}

	public function getUserRoles()
	{
		$id = request()->input('id');

		$userRoles = array();
		foreach(AdminUser::find($id)->roles as $r)
		{
			$userRoles[] = $r->id;
		}

		$roles = array();
		foreach (AdminRole::all() as $r)
		{
			$role['id'] = $r->id;
			$role['checked'] = false;
			$role['name'] = $r->name;

			if (in_array($r->id, $userRoles))
				$role['checked'] = true;

			$roles[] = $role;
		}

		return json_encode($roles);
	}

	// 用户密码修改
	public function resetPassword()
	{   
		if (request()->isMethod('POST'))
		{
			$plaintxt = request()->input('password');

			$id = Auth::id();
			$user = AdminUser::find($id);
			if (!$user)
				return redirect()->back();

			// 密码加密
			$user->password = bcrypt($plaintxt);
			if ($user->save()) {
				return redirect()->back()->with('success', '密码修改成功');
			}
		}

		return redirect()->back();
	}

	public function roles()
	{   
		$roles = AdminRole::getPermission();
		return View('user.roles', compact('roles'));
	}

	public function getRole()
	{
		$id = request()->input('id');
		$role = AdminRole::find($id);
		return json_encode($role);
	}

	public function addRole()
	{   
		if (request()->isMethod('POST'))
		{
			$name = request()->input('name');
			$desc = request()->input('desc');

			$count = DB::table('admin_role')->where('name', $name)->count();
			if ($count > 0)
				return redirect()->back()->with('error', '用户名或者邮箱已经存在');

			if (empty($desc))
				$desc = '';

			$role = new AdminRole;
			$role->name = $name;
			$role->description = $desc;
			$role->save();
		}

		return redirect()->back();
	}

	public function updateRole()
	{
		$id = request()->input('id');
		$role = AdminRole::find($id);
		if (!$role)
			return redirect()->back()->with('error', '角色已经不存在');

		$name = request()->input('name');
		$desc = request()->input('desc');

		if ($name != $role->name)
		{
			$count = DB::table('admin_role')->where('name', $name)->count();
			if ($count > 0)
				return redirect()->back()->with('error', '角色已经存在');
		}

		if (!$desc)
			$desc = '';

		$role->name = $name;
		$role->description = $desc;
		$role->save();

		return redirect()->back();
	}

	public function deleteRole()
	{
		$id = request()->input('id');
		$role = AdminRole::find($id);
		if (!$role)
			return redirect()->back()->with('error', '角色已经不存在');

		$role->delete();
		DB::table('admin_user_role')->where('role_id', $id)->delete();
		DB::table('admin_role_permission')->where('role_id', $id)->delete();

		return redirect()->back()->with('success', '角色'.$role->name.'已被删除');
	}

	public function getRolePermissions()
	{
		$id = request()->input('id');

		$rolePermissions = array();
		foreach(AdminRole::find($id)->permissions as $p)
		{
			$rolePermissions[] = $p->id;
		}

		$permissions = array();
		foreach (AdminPermission::all() as $p)
		{
			$permission['id'] = $p->id;
			$permission['checked'] = false;
			$permission['name'] = $p->name;

			if (in_array($p->id, $rolePermissions))
				$permission['checked'] = true;

			$permissions[] = $permission;
		}

		return json_encode($permissions);
	}

	public function setRolePermissions()
	{
		if (request()->isMethod('POST'))
		{
			$id = request()->input('id');
			$role = AdminRole::find($id);
			if (!$role)
				return redirect()->back()->with('error', '用户已经不存在');

			DB::table('admin_role_permission')->where('role_id', $id)->delete();

			$permissions = AdminPermission::findMany(request('permissions'));
			foreach ($permissions as $p)
				$role->grantPermission($p);
		}

		return redirect()->back();
	}

	public function permissions()
	{
		$permissions = AdminPermission::all();
		return View('user.permissions', compact('permissions'));
	}

	public function addPermission()
	{   
		if (request()->isMethod('POST'))
		{
			$keyword = request()->input('keyword');
			$name = request()->input('name');
			$desc = request()->input('description');

			if (empty($keyword) || empty($name))
				return redirect()->back()->with('error', '权限定义和名称不能为空');

			$count = DB::table('admin_permission')->where('keyword', $keyword)->count();
			if ($count > 0)
				return redirect()->back()->with('error', '权限定义已经存在');

			$count = DB::table('admin_permission')->where('name', $name)->count();
			if ($count > 0)
				return redirect()->back()->with('error', '权限名称已经存在');

			if (empty($desc))
				$desc = '';

			$permission = new AdminPermission;
			$permission->keyword = $keyword;
			$permission->name = $name;
			$permission->description = $desc;
			$permission->save();
		}

		return redirect()->back();
	}

	public function updatePermission()
	{
		$id = request()->input('id');
		$permission = AdminPermission::find($id);
		if (!$permission)
			return redirect()->back()->with('error', '权限定义已经不存在');

		$keyword = request()->input('keyword');
		$name = request()->input('name');
		$desc = request()->input('description');

		if ($keyword != $permission->keyword)
		{
			$count = DB::table('admin_permission')->where('keyword', $keyword)->count();
			if ($count > 0)
				return redirect()->back()->with('error', '角色定义已经存在');
		}
		if ($name != $permission->name)
		{
			$count = DB::table('admin_permission')->where('name', $name)->count();
			if ($count > 0)
				return redirect()->back()->with('error', '角色名称已经存在');
		}

		if (!$desc)
			$desc = '';

		$permission->keyword = $keyword;
		$permission->name = $name;
		$permission->description = $desc;
		$permission->save();

		return redirect()->back();
	}

	public function deletePermission()
	{
		$id = request()->input('id');
		$permission = AdminPermission::find($id);
		if (!$permission)
			return redirect()->back()->with('error', '权限定义已经不存在');

		$permission->delete();
		DB::table('admin_role_permission')->where('permission_id', $id)->delete();

		return redirect()->back()->with('success', '权限定义'.$permission->keyword.'已被删除');
	}
}
