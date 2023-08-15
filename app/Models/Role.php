<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;
    static $admin = 'admin';
    static $user = 'user';
    static $subAdmin = 'sub_admin';

    static $roles = ['admin', 'user', 'sub_admin'];

    protected $guarded = ['id'];

    static public function showingName($name) {
        switch ($name) {
            case Role::$admin:
                return 'Admins';
            case Role::$user:
                return 'Users';
            case Role::$subAdmin:
                return 'Sub Admin';
        }
    }

    public function users()
    {
        return $this->hasMany('App\User', 'role_id', 'id');
    }

    
    public static function getAdminRoleId()
    {
        $id = 1; // user role id

        $role = self::where('role_name', self::$admin)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getUserRoleId()
    {
        $id = 1; // user role id

        $role = self::where('role_name', self::$user)->first();
        return !empty($role) ? $role->id : $id;
    }
    
    public static function getSubAdminRoleId()
    {
        $id = 1; // user role id

        $role = self::where('role_name', self::$subAdmin)->first();
        return !empty($role) ? $role->id : $id;
    }

}
