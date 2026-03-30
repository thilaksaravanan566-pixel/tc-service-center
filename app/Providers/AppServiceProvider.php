<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * This runs AFTER all service providers are registered (including
     * ViewServiceProvider, DatabaseServiceProvider, etc.), so it is safe
     * to query the DB and update config values here.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        $this->loadDynamicSettings();
    }

    /**
     * Merge DB settings into the 'custom' config namespace.
     * Wrapped in try/catch so a missing table (fresh install, migrations
     * not yet run) never breaks the application boot.
     */
    private function loadDynamicSettings(): void
    {
        try {
            // Skip during console commands (artisan migrate, db:seed, etc.)
            // to avoid circular dependency problems.
            if ($this->app->runningInConsole()) {
                return;
            }

            // Guard: only query if the 'settings' table actually exists.
            if (!Schema::hasTable('settings')) {
                return;
            }

            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

            foreach ($settings as $key => $value) {
                config(['custom.' . $key => $value]);
            }
        } catch (\Throwable $e) {
            // Silently fall back to static defaults in config/custom.php.
            // Never let a failed DB read crash the entire application.
            report($e);
        }
    }
}
