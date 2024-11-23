<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Tenant;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'login_id',
        'tenant_id',
        'mst_user_auth_id',
        'user_name'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tenant(): HasOne {
        return $this->hasOne(Tenant::class, "id", "tenant_id");
    }
}
