<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View Composer for injecting global variables into all Blade templates
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            // Theme settings (defaults handled by config/custom.php or injected in AppServiceProvider)
            $view->with('theme_color', config('custom.theme_color', '#1f2937'));
            $view->with('dark_mode', config('custom.dark_mode', 'enabled'));
            
            // Notification synchronization
            $unreadCount = 0;
            if (auth('customer')->check()) {
                try {
                    $unreadCount = \App\Models\CustomerNotification::where('customer_id', auth('customer')->user()->id)
                        ->whereNull('read_at')
                        ->count();
                } catch (\Exception $e) {
                    // Silently fail if table missing during migration
                }
            }
            $view->with('unreadCount', $unreadCount);
        });
    }
}
