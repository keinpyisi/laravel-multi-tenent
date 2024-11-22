<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Base\Tenant;
use Illuminate\Support\Facades\DB;

class SetTenantFromPath {
    public function handle($request, Closure $next) {
        $path = $request->path();
        $segments = explode('/', $path);

        if (count($segments) >= 2 && $segments[0] === 'backend') {
            if ($segments[1] === 'admin') {
                // Admin routes
                DB::statement("SET search_path TO base_tenants");
            } else {
                // Tenant routes
                $tenantSlug = $segments[1];
                $tenant = Tenant::where('domain', $tenantSlug)->first();

                if ($tenant) {
                    // Set the tenant in the app container
                    app()->instance('tenant', $tenant);

                    // Set the database connection to use only the tenant's schema
                    DB::statement("SET search_path TO {$tenant->database}");
                    config(['database.connections.tenant.search_path' => $tenant->database]);
                    DB::purge('tenant');
                    DB::reconnect('tenant');
                    
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
}
