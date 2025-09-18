<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    /**
     * Display the site settings page.
     */
    public function index(): Response
    {
        $groups = [
            'general' => 'General Settings',
            'branding' => 'Branding & Logo',
            'colors' => 'Color Scheme',
            'social' => 'Social Media',
            'contact' => 'Contact Information',
            'system' => 'System Settings',
        ];

        $settings = [];
        foreach ($groups as $groupKey => $groupName) {
            $settings[$groupKey] = [
                'name' => $groupName,
                'settings' => SiteSetting::where('group', $groupKey)->orderBy('display_name')->get()
            ];
        }

        return Inertia::render('SiteSettings/Index', [
            'groups' => $groups,
            'settings' => $settings,
        ]);
    }

    /**
     * Update site settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:site_settings,key',
            'settings.*.value' => 'nullable',
        ]);

        foreach ($request->settings as $settingData) {
            $setting = SiteSetting::where('key', $settingData['key'])->first();
            
            if ($setting) {
                $value = $settingData['value'];
                
                // Handle boolean values
                if ($setting->type === 'boolean') {
                    $value = $value ? '1' : '0';
                }
                
                // Handle image uploads
                if ($setting->type === 'image' && $request->hasFile("settings.{$settingData['key']}.file")) {
                    $file = $request->file("settings.{$settingData['key']}.file");
                    
                    // Delete old file if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    // Store new file
                    $path = $file->store('site-settings', 'public');
                    $value = $path;
                }
                
                $setting->update(['value' => $value]);
            }
        }

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }

    /**
     * Upload an image for a setting.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'key' => 'required|string|exists:site_settings,key',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $setting = SiteSetting::where('key', $request->key)->first();
        
        if (!$setting || $setting->type !== 'image') {
            return response()->json(['error' => 'Invalid setting or setting type'], 400);
        }

        // Delete old file if exists
        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        // Store new file
        $path = $request->file('image')->store('site-settings', 'public');
        $setting->update(['value' => $path]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Remove an image for a setting.
     */
    public function removeImage(Request $request)
    {
        $request->validate([
            'key' => 'required|string|exists:site_settings,key',
        ]);

        $setting = SiteSetting::where('key', $request->key)->first();
        
        if (!$setting || $setting->type !== 'image') {
            return response()->json(['error' => 'Invalid setting or setting type'], 400);
        }

        // Delete file if exists
        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->update(['value' => null]);

        return response()->json(['success' => true]);
    }

    /**
     * Get public settings for frontend use.
     */
    public function getPublicSettings()
    {
        $settings = SiteSetting::getPublicSettings();
        
        return response()->json($settings);
    }

    /**
     * Reset settings to default values.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'group' => 'nullable|string|in:general,branding,colors,social,contact,system',
        ]);

        if ($request->group) {
            // Reset specific group
            SiteSetting::where('group', $request->group)->delete();
            $this->seedGroup($request->group);
        } else {
            // Reset all settings
            SiteSetting::truncate();
            $this->seedAllSettings();
        }

        return redirect()->back()->with('success', 'Settings reset successfully.');
    }

    /**
     * Seed a specific group of settings.
     */
    private function seedGroup(string $group)
    {
        $seeder = new \Database\Seeders\SiteSettingSeeder();
        $seeder->run();
    }

    /**
     * Seed all settings.
     */
    private function seedAllSettings()
    {
        $seeder = new \Database\Seeders\SiteSettingSeeder();
        $seeder->run();
    }
}
