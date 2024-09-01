<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function permissions() {
        $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }
}
