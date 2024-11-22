<?php

namespace App\Models\Base;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.users';
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'login_id',
        'user_name',
        'auth_id',
        'insert_user_id',
        'update_user_id',
        'del_flag'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
