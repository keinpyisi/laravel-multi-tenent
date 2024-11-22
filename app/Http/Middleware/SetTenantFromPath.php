<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Base\Tenant;
use Illuminate\Support\Facades\DB;

class SetTenantFromPath
{
    public function handle($request, Closure $next)
    {
        $path = $request->path();
        $segments = explode('/', $path);
        if (!empty($segments) && $segments[0] !== 'backend') {
            $tenantSlug = $segments[0];
            DB::statement("SET search_path TO base_tenants");
            $tenant = Tenant::where('domain', $tenantSlug)->first();

            if ($tenant) {
                // Set the tenant in the app container
                app()->instance('tenant', $tenant);

                // Set the database connection to use the tenant's schema and base_tenants
                DB::statement("SET search_path TO {$tenant->database}, base_tenants, public");
                config(['database.connections.tenant.search_path' => "{$tenant->database},base_tenants,public"]);
                DB::purge('tenant');
                DB::reconnect('tenant');
            } else {
                abort(404);
            }
        } else {
            // For non-tenant routes, ensure base_tenants is in the search path
            DB::statement("SET search_path TO base_tenants, public");
        }

        return $next($request);
    }
}