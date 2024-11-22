<?php

namespace App\Console\Commands;

use App\Models\Base\Tenant;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class three_CreateTenant extends Command
{
    protected $signature = 'tenant:create {name}';
    protected $description = 'Create a new tenant';
    
    //php artisan tenants:link
    public function handle()
    {
       // Get and display the current database configuration
        $dbConfig = config('database.connections.' . config('database.default'));
        $this->info("Current Database Configuration:");
        $this->table(['Key', 'Value'], collect($dbConfig)->map(function ($value, $key) {
            return [$key, is_array($value) ? json_encode($value) : $value];
        })->toArray());
        
        $name = $this->argument('name');
        $domain = Str::slug($name);
        $schema = $domain;
        
        DB::statement("SET search_path TO base_tenants");
        // Get all tables in the base_tenants schema
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'base_tenants'");
        // If you want to display the tables in a nice table format
        $tableData = collect($tables)->map(function ($table) {
            return [$table->table_name];
        })->toArray();

        $this->table(['Table Name'], $tableData);
        // Check if the tenants table exists in the base_tenants schema
            $tenantTableExists = DB::select("
            SELECT EXISTS (
                SELECT FROM information_schema.tables 
                WHERE table_schema = 'base_tenants' 
                AND table_name = 'tenants'
            )
        ")[0]->exists;

        if (!$tenantTableExists) {
            $this->error('The tenants table does not exist in the base_tenants schema. Please run migrations first.');
            return;
        }

        $tenant = Tenant::create([
            'name' => $name,
            'domain' => $domain,
            'database' => $schema, // We're using 'database' to store the schema name
        ]);

        $this->info("Tenant created successfully.");
        $this->info("Name: {$name}");
        $this->info("Domain: {$domain}");
        $this->info("Schema: {$schema}");

        // Create the tenant's schema
        $this->createSchema($schema);
        DB::statement("SET search_path TO $schema");
        // Run tenant-specific migrations
        $this->runTenantMigrations($schema);

        // Create custom folder for the tenant
        $this->createCustomFolder($domain);
        DB::statement("SET search_path TO base_tenants");

        $this->info("Tenant setup completed.");
    }

    private function createSchema($name)
    {
        $query = "SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?";
        $schema = DB::select($query, [$name]);

        if (empty($schema)) {
            DB::statement("CREATE SCHEMA \"$name\"");
            $this->info("Schema $name created successfully.");
        } else {
            $this->info("Schema $name already exists.");
        }
    }

    private function runTenantMigrations($schema)
    {
        $this->info("Running migrations for tenant schema: {$schema}");

        // Set the search path to the tenant's schema
        DB::statement("SET search_path TO \"$schema\"");

        // Run the migrations
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // Reset the search path
        DB::statement("SET search_path TO public");

        $this->info("Tenant migrations completed.");
    }

    private function createCustomFolder($domain)
    {
        $customFolder = tenant_path($domain);
        if (!file_exists($customFolder)) {
            mkdir($customFolder, 0755, true);
            $this->info("Custom folder created: {$customFolder}");

            // Create additional subdirectories if needed
            mkdir(tenant_path($domain, 'files'), 0755, true);
            mkdir(tenant_path($domain, 'cache'), 0755, true);

            // Ensure the web server has write permissions
            chmod($customFolder, 0775);
        } else {
            $this->info("Custom folder already exists: {$customFolder}");
        }

        // Create a .gitignore file to prevent tenant data from being committed
        $gitignorePath = storage_path('tenants/.gitignore');
        if (!file_exists($gitignorePath)) {
            file_put_contents($gitignorePath, "*\n!.gitignore\n");
            $this->info("Created .gitignore in tenants directory");
        }
    }
}