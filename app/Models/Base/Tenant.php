<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.tenants';
    protected $fillable = ['name', 'domain', 'database'];

    // If you're using UUID, uncomment the following line
    // use Illuminate\Database\Eloquent\Concerns\HasUuids;
}