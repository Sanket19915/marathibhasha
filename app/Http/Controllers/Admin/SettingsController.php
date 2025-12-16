<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $googleSettings = Setting::getByGroup('google');
        $analyticsSettings = Setting::getByGroup('analytics');
        
        return view('admin.settings', [
            'googleSettings' => $googleSettings,
            'analyticsSettings' => $analyticsSettings,
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'google_client_id' => 'nullable|string',
            'google_client_secret' => 'nullable|string',
            'google_redirect_uri' => 'nullable|string|url',
            'google_analytics_id' => 'nullable|string',
        ]);

        // Update Google settings
        if ($request->has('google_client_id')) {
            Setting::set('google_client_id', $request->google_client_id);
        }
        
        if ($request->has('google_client_secret')) {
            Setting::set('google_client_secret', $request->google_client_secret);
        }
        
        if ($request->has('google_redirect_uri')) {
            Setting::set('google_redirect_uri', $request->google_redirect_uri);
        }

        // Update Analytics settings
        if ($request->has('google_analytics_id')) {
            Setting::set('google_analytics_id', $request->google_analytics_id);
        }

        // Clear cache if you're caching settings
        Cache::forget('settings');

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }
}
