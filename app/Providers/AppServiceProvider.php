<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Console\Commands\LinkTenantStorageCommand;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        Carbon::setLocale('ja');
        //
        if ($this->app->runningInConsole()) {
            $this->commands([
                LinkTenantStorageCommand::class,
            ]);
        }
        // admin レイアウトの登録
        Blade::component('admin.layouts.app', 'app-layout');

        // admin コンポーネントの名前空間設定
        Blade::componentNamespace('App\\View\\Components\\Admin', 'admin');

        // admin ビューの名前空間追加
        View::addNamespace('admin', resource_path('views/admin'));

        // admin コンポーネントのパスを指定
        Blade::componentNamespace('', 'admin');
    }
}
