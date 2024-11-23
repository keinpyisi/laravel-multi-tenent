<?php

namespace App\Http\Controllers\Admin;


use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client_Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TenantController extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $header_js_defines = [
            'resources/js/clients/index.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];
        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);

        $tenents = Tenant::activeWith()->paginate(100);
        return view('admin.pages.tenants.list', compact('tenents'));
    }

    public function create() {
        $header_js_defines = [
            'resources/js/clients/create.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];
        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);
        return view('admin.pages.tenants.create');
    }

    public function store(Client_Validation $request) {
        // Validation logic
        if ($request->fails()) {
            return redirect()->back()->withInput();
        }
        $account_name = $request->account_name;
        $domain = $account_name;
        $database = $account_name;
    }

    public function show(int $id) {
        // $r = [
        //     "id" => $id,
        // ];

        // $validator = Validator::make($r, [
        //     "id" => ["required", "exists:sns,id"],
        // ]);

    }

    public function edit(int $id) {
    }

    public function update(Request $request, int $id) {
        // $validator = Validator::make($request->all(), [
        //     "name" => ["required"],
        // ]);

        // if ($validator->fails()) {

        // }

    }

    public function destroy(int $id) {
    }
}
