<?php

namespace App\Http\Controllers\Admin;

use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TenantController extends Controller {
    //
    public function index() {
        $tenants = Tenant::all();
        return view('admin.pages.tenants.list', compact('tenants'));
    }
}
