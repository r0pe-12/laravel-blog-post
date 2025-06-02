<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    //
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function regular_user()
    {
        $role = Role::findOrNew(3);
        if (!$role->id) {
            $role->id = 3;
            $role->name = 'User';
            $role->save();
        }
        return $role;
    }

    public static function guest()
    {
        $role = Role::findOrNew(2);
        if (!$role->id) {
            $role->id = 2;
            $role->name = 'Guest';
            $role->save();
        }
        return $role;
    }

    public static function admin()
    {
        $role = Role::findOrNew(1);
        if (!$role->id) {
            $role->id = 1;
            $role->name = 'Administrator';
            $role->save();
        }
        return $role;
    }
}
