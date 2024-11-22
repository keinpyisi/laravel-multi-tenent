<?php
namespace App\Models\Base;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TenantAdmin extends Authenticatable
{
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.tenants';
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}