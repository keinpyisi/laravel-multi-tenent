<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();

        static::updating(function ($user) {
            // Set the update_user_id to the currently authenticated user's ID
            // if (auth()->check()) {
            //     $user->update_user_id = auth()->user()->id;
            // }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'login_id',
        'tenant_id',
        'mst_user_auth_id',
        'user_name',
        'update_user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Mutator to hash password before saving
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function tenant(): HasOne {
        return $this->hasOne(Tenant::class, "id", "tenant_id");
    }
}
