<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Models\Base\Tenant;
use App\Models\Tenant\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Client_Validation;
use \App\Models\Tenant\Tenant as Client_Tenant;
use App\Http\Requests\Admin\Client_Edit_Validation;

class UserController extends Controller {
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

        // Fetch active tenants and paginate
        $tenents = Tenant::activeWith()->paginate(20);
        log_message('Admin-specific log: Tenants list', $tenents);

        // Return the view with the paginated tenants
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
        try {
            DB::beginTransaction();

            DB::commit();
            // Redirect to the tenant's index with success
            return redirect()->route('admin.tenants.index')->with(
                'success',
                [
                    'title' => __('lang.success_title'),
                    'text' => __('lang.success', ['attribute' => $data['client_name']]),
                ]
            );
        } catch (Exception $ex) {
            Log::error($ex);
            Log::error('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);

            DB::rollBack();

            return redirect()->route('admin.tenants.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }


    public function show(int $id) {
        DB::statement("SET search_path TO base_tenants");

        $r = [
            "id" => $id,
        ];

        $validator = Validator::make($r, [
            "id" => ["required", "exists:tenants,id"],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return 404 if validation fails
            abort(404);
        }
        $header_js_defines = [
            'resources/js/clients/show.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);


        return view('admin.pages.tenants.show', compact('tenant', 'users', 'all_usage', 'client_usage'));
    }


    public function edit(int $id) {
    }

    public function update(Client_Edit_Validation $request, int $id) {
        DB::beginTransaction();
        try {
            DB::statement("SET search_path TO base_tenants");
            DB::commit();

            // Optionally, you can return a response or redirect
            return redirect()->route('admin.tenants.index')->with(
                'success',
                [
                    'title' => __('lang.success_title'),
                    'text' => __('lang.success', ['attribute' => '']),
                ]
            );
        } catch (Exception $ex) {
            Log::error($ex);
            Log::error('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);

            DB::rollBack();

            return redirect()->route('admin.tenants.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }


    public function destroy($id) {
        try {
            DB::beginTransaction();


            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('admin.tenants.index')->with('success', [
                'title' => __('lang.success_title'),
                'text' => __('lang.tenant_deleted', ['tenant' => '']), // Ensure this lang exists
            ]);
        } catch (Exception $ex) {
            // Log errors
            Log::error('Error occurred during tenant deletion: ', ['exception' => $ex->getMessage()]);

            // Rollback transaction in case of error
            DB::rollBack();

            // Redirect with error message
            return redirect()->route('admin.tenants.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        }
    }
}
