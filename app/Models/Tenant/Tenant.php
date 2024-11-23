<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model {

    const DELETED = 1;
    const ACTIVE = 0;
    protected $fillable = [
        'id',
        'client_name',
        'account_name',
        'domain',
        'database',
        'kana',
        'logo',
        'genre',
        'person_in_charge',
        'tel',
        'address',
        'post_code',
        'fax_number',
        'e_mail',
        'homepage',
        'support_mail',
        'note',
        'insert_user_id',
        'update_user_id',
        'del_flag',
        'tenent_unique_key'
    ];

    protected $casts = [
        'insert_user_id' => 'integer',
        'update_user_id' => 'integer',
        'del_flag' => 'boolean',
    ];

    // If you want to use created_at and updated_at
    public $timestamps = true;
    // Relationship to users
    public function tenantUsers(): HasMany {
        return $this->hasMany(User::class, 'tenant_id');
    }

    // Scope to get active tenants
    public function scopeActiveWith(Builder $query): Builder {
        return $query->where('del_flag', self::ACTIVE);
    }
    // If you're using UUID, uncomment the following line
    // use Illuminate\Database\Eloquent\Concerns\HasUuids;
}
