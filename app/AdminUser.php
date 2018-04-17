<?php

namespace App;

use App\AdminRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
	use Notifiable;

	protected $table = 'admin_user';

	protected $fillable = [ 'name', 'email', 'password' ];

	protected $hidden = [ 'password', 'remember_token' ];

	public static function users()
	{
		$users = array();
		foreach (AdminUser::all() as $u)
		{
			$roles = array();
			foreach ($u->roles as $r)
			{
				$roles[] = $r->name;
			}

			$user['name'] = $u->name;
			$user['email'] = $u->email;
			$user['roles'] = implode(', ', $roles);

			$users[$u->id] = $user;
		}

		return $users;
	}

	public function roles()
	{
		return $this->belongsToMany(AdminRole::class, 'admin_user_role', 'user_id', 'role_id');
	}

	// 判断用户有没有某个角色，某些角色
	public function isInRoles($roles)
	{
		return !!$roles->intersect($this->roles)->count();
	}

	// 给用户分配角色
	public function assignRole($role)
	{
		return $this->roles()->save($role);
	}

	// 取消用户分配的角色
	public function deleteRole($role)
	{
		return $this->roles()->detach($role);
	}

	// 用户有没有权限
	public function hasPermission($permission)
	{
		return $this->isInRoles($permission->roles);
	}
}
