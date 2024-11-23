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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\Client_Validation;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Client_Edit_Validation;
use \App\Models\Tenant\Tenant as Client_Tenant;

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

        // Fetch active tenants and paginate
        $tenents = Tenant::activeWith()->paginate(20);

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

            // Merge additional data into the request
            $request->merge([
                'insert_user_id' => 1,
                'update_user_id' => 1,
                'domain' => $request->account_name,
                'database' => $request->account_name,
            ]);

            // Handle file upload for logo
            $data = $request->all();
            $this->createCustomFolder($data['domain']);

            if ($request->hasFile('logo')) {
                try {
                    $logo = $request->file('logo');

                    // Get the original extension
                    $extension = $logo->getClientOriginalExtension(); // "png", "jpg", etc.

                    // Set the logo name
                    $logoName = 'logo.' . $extension;

                    // Define the directory structure (corrected path)
                    $tenantLogoPath = $data['domain'] . '/logo';  // No need to include 'tenants/'

                    // Ensure the directory exists
                    $logoDirectory = storage_path('app/tenants/' . $tenantLogoPath);  // Ensure the full path includes 'tenants/'
                    if (!is_dir($logoDirectory)) {
                        mkdir($logoDirectory, 0775, true);  // Create directory if it doesn't exist
                    }

                    // Store the file in the specified location
                    $logoPath = $logo->storeAs($tenantLogoPath, $logoName, 'tenant');

                    // Check if the file exists
                    if (Storage::disk('tenant')->exists($logoPath)) {
                        // Get the full path
                        $fullPath = Storage::disk('tenant')->path($logoPath);
                        Log::error(['message' => 'File uploaded and exists', 'full_path' => $fullPath]);
                    } else {
                        Log::error('Error uploading logo: No File');
                    }

                    // Save the file path to the database or log it
                    $data['logo'] = $logoPath;
                } catch (Exception $e) {
                    Log::error('Error uploading logo: ' . $e->getMessage());
                    return back()->withErrors(['error' => ['title' => 'An error occurred while uploading the logo.']]);
                }
            } else {
                Log::error('Logo file upload failed for tenant: ' . $data['domain']);
            }


            // Add tenant unique key
            $data['tenent_unique_key'] = Str::uuid()->toString();  // Ensure spelling matches the database column

            // Create the Tenant with the validated data
            $tenant = Tenant::create($data);

            // Handle schema creation and migrations
            $this->createSchema($data['database']);
            DB::statement("SET search_path TO {$data['database']}");
            $this->runTenantMigrations($data['database']);

            DB::statement("SET search_path TO base_tenants");

            DB::commit();

            DB::beginTransaction();

            DB::statement("SET search_path TO {$data['database']}");
            $data['id'] = $tenant->id;
            $tenant = Client_Tenant::create($data);

            User::create([
                'login_id' => $data['login_id'],
                'user_name' => $data['login_id'],
                'password' => bcrypt($data['login_id']), // Hash the password
                'tenant_id' => $tenant->id,

            ]);
            DB::statement("SET search_path TO base_tenants");

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

        $tenant = Tenant::findOrFail($id);
        DB::statement("SET search_path TO {$tenant->database}");
        $users = User::where('tenant_id', $tenant->id)->paginate(100);
        DB::statement("SET search_path TO base_tenants");
        return view('admin.pages.tenants.show', compact('tenant', 'users'));
    }


    public function edit(int $id) {
    }

    public function update(Client_Edit_Validation $request, int $id) {
        DB::beginTransaction();
        try {
            // Find the tenant or fail if not found
            $tenant = Tenant::findOrFail($id);
            $client_name = $tenant->client_name;
            // Validate the request
            $validatedData = $request->validated();
            if ($request->hasFile('logo')) {
                try {
                    $logo = $request->file('logo');

                    // Get the original extension
                    $extension = $logo->getClientOriginalExtension(); // "png", "jpg", etc.

                    // Set the logo name
                    $logoName = 'logo.' . $extension;

                    // Define the directory structure (corrected path)
                    $tenantLogoPath = $tenant->domain . '/logo';  // No need to include 'tenants/'

                    // Ensure the directory exists
                    $logoDirectory = storage_path('app/tenants/' . $tenantLogoPath);  // Ensure the full path includes 'tenants/'
                    if (!is_dir($logoDirectory)) {
                        mkdir($logoDirectory, 0775, true);  // Create directory if it doesn't exist
                    }

                    // Store the file in the specified location
                    $logoPath = $logo->storeAs($tenantLogoPath, $logoName, 'tenant');

                    // Check if the file exists
                    if (Storage::disk('tenant')->exists($logoPath)) {
                        // Get the full path
                        $fullPath = Storage::disk('tenant')->path($logoPath);
                        Log::error(['message' => 'File uploaded and exists', 'full_path' => $fullPath]);
                    } else {
                        Log::error('Error uploading logo: No File');
                    }

                    // Save the file path to the database or log it
                    $validatedData['logo'] = $logoPath;
                } catch (Exception $e) {
                    Log::error('Error uploading logo: ' . $e->getMessage());
                    return back()->withErrors(['error' => ['title' => 'An error occurred while uploading the logo.']]);
                }
            } else {
                Log::error('Logo file upload failed for tenant: ' . $client_name);
            }
            // Update the tenant with the validated data
            $tenant->update($validatedData);
            DB::statement("SET search_path TO {$tenant->database}");
            $client_tenant = Client_Tenant::findOrFail($id);
            $client_tenant->update($validatedData);
            DB::statement("SET search_path TO base_tenants");
            DB::commit();

            // Optionally, you can return a response or redirect
            return redirect()->route('admin.tenants.index')->with(
                'success',
                [
                    'title' => __('lang.success_title'),
                    'text' => __('lang.success', ['attribute' => $client_name]),
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

            // Find the tenant by ID
            $tenant = Tenant::findOrFail($id);

            // Get the tenant's domain to remove associated files
            $tenantDomain = $tenant->domain;
            $tenantLogoPath = 'tenants/' . $tenantDomain . '/logo';

            // Delete the tenant's logo file if it exists
            if (Storage::exists($tenantLogoPath)) {
                Storage::delete($tenantLogoPath);
            }

            // Drop tenant-specific schema and database
            $this->dropSchema($tenant->database); // Make sure this method exists to handle schema drop
            // Delete the tenant record itself
            $tenant->delete();

            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('admin.tenants.index')->with('success', [
                'title' => __('lang.success_title'),
                'text' => __('lang.tenant_deleted', ['tenant' => $tenant->client_name]), // Ensure this lang exists
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
    protected function dropSchema($database) {
        try {
            DB::statement("DROP SCHEMA IF EXISTS {$database} CASCADE");
        } catch (Exception $ex) {
            Log::error('Error while dropping schema for tenant: ' . $database, ['exception' => $ex->getMessage()]);
        }
    }


    private function createSchema($name) {
        DB::statement("CREATE SCHEMA \"$name\"");
    }

    private function runTenantMigrations($schema) {
        // Set the search path to the tenant's schema
        DB::statement("SET search_path TO \"$schema\"");

        // Run the migrations
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // Reset the search path
        DB::statement("SET search_path TO public");
    }

    private function createCustomFolder($domain) {
        $customFolder = tenant_path($domain);

        // Create the main tenant folder if it doesn't exist
        if (!file_exists($customFolder)) {
            mkdir($customFolder, 0755, true);

            // Create additional subdirectories if needed
            mkdir(tenant_path($domain, 'files'), 0755, true);
            mkdir(tenant_path($domain, 'cache'), 0755, true);

            // Ensure the web server has write permissions
            chmod($customFolder, 0775);
        }

        // Create a 'logo' subfolder inside the tenant folder
        $logoFolder = tenant_path($domain, 'logo');
        if (!file_exists($logoFolder)) {
            mkdir($logoFolder, 0755, true);
        }

        // Create a .gitignore file to prevent tenant data from being committed
        $gitignorePath = storage_path('tenants/.gitignore');
        if (!file_exists($gitignorePath)) {
            file_put_contents($gitignorePath, "*\n!.gitignore\n");
        }
    }
}
