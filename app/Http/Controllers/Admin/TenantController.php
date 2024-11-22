<?php

namespace App\Http\Controllers\Admin;


use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TenantController extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $tenents = Tenant::activeWith()->paginate(100);
        return view('admin.pages.tenants.list', compact('tenents'));
    }

    public function create() {
    }

    public function store(Request $request) {
        // $validator = Validator::make($request->all(), [
        //     "name" => ["required"],
        // ]);

        // if ($validator->fails()) {
        //     return $this->error_send(new ValidationException($validator), "A-006", 422);
        // }
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
