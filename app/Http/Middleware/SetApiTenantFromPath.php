<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SetApiTenantFromPath {
    public function handle($request, Closure $next) {
        // Basic Auth check
        if (!$this->checkBasicAuth($request)) {
            return response('Unauthorized', 401)->header('WWW-Authenticate', 'Basic realm="Restricted Area"');
        }
        $path = $request->path();
        $segments = explode('/', $path);
        if (count($segments) >= 2 && $segments[1] === 'backend') {
            if ($segments[2] === 'admin') {
                $tenantLogPath = storage_path('logs/admins/');
                config(['logging.channels.tenant' => [
                    'driver' => 'daily',
                    'path' => $tenantLogPath . '/laravel.log',
                    'level' => 'debug',
                    'days' => 14, // Keep logs for 14 days
                ]]);
                // Admin routes
                DB::statement("SET search_path TO base_tenants");
            } else {
                // Tenant routes
                $tenantSlug = $segments[2];
                $tenant = Tenant::where('domain', $tenantSlug)->first();

                if ($tenant) {
                    // Set the tenant in the app container
                    app()->instance('tenant', $tenant);

                    // Set the database connection to use only the tenant's schema
                    DB::statement("SET search_path TO {$tenant->database}");
                    config(['database.connections.tenant.search_path' => $tenant->database]);
                    DB::purge('tenant');
                    DB::reconnect('tenant');
                    // Set custom daily log path for the tenant
                    $tenantLogPath = storage_path('logs/tenants/' . $tenantSlug);
                    config(['logging.channels.tenant' => [
                        'driver' => 'daily',
                        'path' => $tenantLogPath . '/laravel.log',
                        'level' => 'debug',
                        'days' => 14, // Keep logs for 14 days
                    ]]);
                } else {
                    abort(404);
                }
            }
        } else {
            // For routes not starting with 'backend', set to public schema
            DB::statement("SET search_path TO public");
        }

        return $next($request);
    }
    private function checkBasicAuth(Request $request) {
        if (!Storage::disk('tenant')->exists('.htpasswd')) {
            return true;
            // Process the file contents as needed
        }
        $authHeader = $request->header('Authorization');

        if (empty($authHeader)) {
            return false;
        }

        // The "Basic" keyword and the base64-encoded credentials
        list($type, $credentials) = explode(' ', $authHeader, 2);

        if (strtolower($type) != 'basic') {
            return false;
        }

        // Decode base64 credentials
        $decodedCredentials = base64_decode($credentials);
        list($username, $password) = explode(':', $decodedCredentials, 2);

        // Read the .htpasswd file from the storage directory
        if (Storage::disk('tenant')->exists('.htpasswd')) {
            $htpasswdContents = Storage::disk('tenant')->get('.htpasswd');

            // Parse the .htpasswd file
            $lines = explode("\n", $htpasswdContents);
            foreach ($lines as $line) {
                if (empty($line)) continue;
                // Split the line into username and password hash
                list($storedUsername, $storedPassword) = explode(':', $line, 2);

                // Check if username matches and password matches the hash
                if ($username === $storedUsername && $this->verifyPassword($password, $storedPassword)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function verifyPassword($password, $storedPassword) {
        // Check if the password matches the stored hash (for htpasswd)
        // Laravel doesn't have a built-in method for htpasswd hash verification,
        // so we use an external package or a custom solution to verify the password.

        // For example, use an Apache htpasswd password hash verification method:
        if (Hash::check($password, $storedPassword)) {
            return true;
        }

        return false;
    }
}
