<?php

namespace App\Http\Controllers\Tenents;

use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UserController extends Controller {
    //
    public function index() {
        // User::create([
        //     'login_id' => 'ascon',
        //     'email' => 'ascon@ascon.co.jp',
        //     'user_name' => 'ascon',
        //     'password' => 'asadsadsafd',
        //     'tenant_id' => 1,
        //     'mst_user_auth_id' => 1,
        // ]);
        $users = User::all();
        // Use the tenant-specific daily log channel
        Log::channel('tenant')->info('Tenant-specific log started for ' . $users);
        dd($users);
    }
}
