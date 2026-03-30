<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private function settings(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    // ── Individual pages ─────────────────────────────────

    public function homepage(): View
    {
        return view('admin.settings.homepage', ['settings' => $this->settings()]);
    }

    public function header(): View
    {
        return view('admin.settings.header', ['settings' => $this->settings()]);
    }

    public function raceMap(): View
    {
        return view('admin.settings.race-map', ['settings' => $this->settings()]);
    }

    public function footer(): View
    {
        return view('admin.settings.footer', ['settings' => $this->settings()]);
    }

    // ── Legacy redirect ──────────────────────────────────

    public function index(): View
    {
        return $this->homepage();
    }

    // ── Single update handler ────────────────────────────

    public function update(Request $request): RedirectResponse
    {
        $fields = [
            'site_name', 'tagline', 'accent_color', 'season_year',
            'contact_email', 'contact_phone', 'address',
            'facebook', 'youtube', 'instagram',
            'homepage_mode', 'homepage_pigeon_count', 'homepage_team_count', 'homepage_content',
            'live_event_enabled', 'live_event_type', 'live_event_title', 'live_event_description',
            'live_event_lat', 'live_event_lng', 'live_event_liberation_name',
            'entries_enabled',
            'race_map_points', 'race_map_loft_lat', 'race_map_loft_lng',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        $redirect = $request->input('_redirect', 'admin.settings.homepage');

        return redirect()->route($redirect)->with('success', 'Settings saved.');
    }
}
