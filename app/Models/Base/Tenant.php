<?php

namespace App\Models\Base;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model {
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.tenants';

    const DELETED = 1;
    const ACTIVE = 0;
    protected $fillable = [
        'client_name',
        'account_name',
        'domain',
        'database',
        'kana',
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
    public function tenent_users(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function scopeActiveWith(Builder $query): Builder {
        return $query->where('del_flag', $this::ACTIVE);
    }
    // If you're using UUID, uncomment the following line
    // use Illuminate\Database\Eloquent\Concerns\HasUuids;
}
