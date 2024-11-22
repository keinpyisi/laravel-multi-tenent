<?php

if (!function_exists('tenant_path')) {
    /**
     * Get the path to a tenant-specific directory.
     *
     * @param string $tenantDomain
     * @param string $path
     * @return string
     */
    function tenant_path($tenantDomain, $path = '')
    {
        return storage_path('tenants/' . $tenantDomain . ($path ? '/' . $path : ''));
    }
}

if (!function_exists('current_tenant')) {
    /**
     * Get the current tenant.
     *
     * @return \App\Models\Base\Tenant|null
     */
    function current_tenant()
    {
        return app('tenant');
    }
}

if (!function_exists('tenant_asset')) {
    /**
     * Generate a tenant-specific asset path.
     *
     * @param string $path
     * @return string
     */
    function tenant_asset($path)
    {
        $tenant = current_tenant();
        if (!$tenant) {
            throw new \Exception('No tenant set for asset path.');
        }
        return asset('tenants/' . $tenant->domain . '/' . ltrim($path, '/'));
    }
}