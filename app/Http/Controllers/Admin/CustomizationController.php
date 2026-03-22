<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\FeatureToggle;
use App\Models\NotificationTemplate;
use App\Models\ServiceOrder;
use App\Models\ProductOrder;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CustomizationController extends Controller
{
    /**
     * Main Customization Hub — Global Settings, Theme, Toggles, Notifications.
     */
    public function index()
    {
        $settings  = Setting::all()->keyBy('key');
        $toggles   = FeatureToggle::all()->keyBy('name');
        $templates = NotificationTemplate::all();

        // Dashboard stats for widget builder
        $stats = [
            'services_pending' => ServiceOrder::whereIn('status', ['pending','diagnosing'])->count(),
            'revenue_today'    => ProductOrder::whereDate('created_at', today())->sum('total_price'),
            'low_stock_parts'  => SparePart::where('stock', '<=', 5)->count(),
            'technicians'      => User::where('role', 'technician')->count(),
            'customers'        => Customer::count(),
        ];

        return view('admin.customization.index', compact('settings', 'toggles', 'templates', 'stats'));
    }

    /**
     * Save global business settings.
     */
    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', 'logo', 'favicon', 'homepage_banner']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value, $this->resolveGroup($key));
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('company_logo', $path, 'theme');
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('company_favicon', $path, 'theme');
        }

        if ($request->hasFile('homepage_banner')) {
            $path = $request->file('homepage_banner')->store('settings/banners', 'public');
            Setting::set('homepage_banner', $path, 'theme');
        }

        // Clear config cache so new values take effect immediately
        Artisan::call('config:clear');

        return back()->with('success', 'Settings saved and applied successfully.');
    }

    /**
     * Save feature module toggles.
     */
    public function updateToggles(Request $request)
    {
        $allToggles = FeatureToggle::all();

        foreach ($allToggles as $toggle) {
            $isActive = $request->has('toggle_' . $toggle->name);
            $toggle->update(['is_active' => $isActive]);
        }

        return back()->with('success', 'Module configuration updated.');
    }

    /**
     * Helper to determine which settings group a key belongs to.
     */
    private function resolveGroup(string $key): string
    {
        $themeKeys = ['theme_color', 'dark_mode', 'company_logo', 'company_favicon', 'homepage_banner'];
        $contactKeys = ['company_gst', 'support_phone', 'support_email', 'company_address'];

        if (in_array($key, $themeKeys)) return 'theme';
        if (in_array($key, $contactKeys)) return 'contact';
        return 'general';
    }
}
