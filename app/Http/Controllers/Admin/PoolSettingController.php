<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class PoolSettingController extends Controller
{
    protected array $defaults = [
        'pool_hotspot_amounts' => '["50p","£1","£2","£3","£5","£10"]',
        'pool_race_amounts' => '["£2","£3","£5","£10","£50","£100"]',
        'pool_hotspot_nom' => '£5 Nom',
        'pool_race_nom' => '£30 Nom',
        'pool_hotspot_footer' => "All Pools must be paid in full prior to the liberation, Pools will be paid out 1 in every 10.\nThe £5.00 Nomination entry is unlimited e.g you can enter all your birds but it will be the first\nNom bird timed which takes all Nom monies ( not paid 1 in 10 )",
        'pool_race_footer' => "All Pools must be paid in full prior to the liberation, Pools will be paid out 1 in every 10.\nThe £30.00 Nomination entry is unlimited e.g you can enter all your birds but it will be the first\nNom bird timed which takes all Nom monies ( not paid 1 in 10 )",
        'pool_pdf_rows' => '15',
    ];

    public function index(): View
    {
        $settings = [];
        foreach ($this->defaults as $key => $default) {
            $settings[$key] = Setting::get($key, $default);
        }
        $settings['entry_year'] = Setting::get('entry_year', (string) date('Y'));

        $hotspotPdfExists = file_exists(public_path('downloads/pool-hotspots.pdf'));
        $racePdfExists = file_exists(public_path('downloads/pool-races.pdf'));

        return view('admin.pool-pdf', compact('settings', 'hotspotPdfExists', 'racePdfExists'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'pool_hotspot_amounts_raw' => 'required|string',
            'pool_race_amounts_raw' => 'required|string',
            'pool_hotspot_nom' => 'required|string|max:20',
            'pool_race_nom' => 'required|string|max:20',
            'pool_hotspot_footer' => 'required|string|max:1000',
            'pool_race_footer' => 'required|string|max:1000',
            'pool_pdf_rows' => 'required|integer|min:5|max:30',
        ]);

        // Convert comma-separated amounts to JSON arrays
        $hotspotAmounts = array_map('trim', explode(',', $request->input('pool_hotspot_amounts_raw')));
        $raceAmounts = array_map('trim', explode(',', $request->input('pool_race_amounts_raw')));

        Setting::set('pool_hotspot_amounts', json_encode(array_values(array_filter($hotspotAmounts))));
        Setting::set('pool_race_amounts', json_encode(array_values(array_filter($raceAmounts))));
        Setting::set('pool_hotspot_nom', $request->input('pool_hotspot_nom'));
        Setting::set('pool_race_nom', $request->input('pool_race_nom'));
        Setting::set('pool_hotspot_footer', $request->input('pool_hotspot_footer'));
        Setting::set('pool_race_footer', $request->input('pool_race_footer'));
        Setting::set('pool_pdf_rows', $request->input('pool_pdf_rows'));

        if ($request->input('action') === 'generate') {
            $this->generatePdf('hotspot');
            $this->generatePdf('race');
            return back()->with('success', 'Settings saved and both PDFs generated.');
        }

        return back()->with('success', 'Pool settings saved.');
    }

    public function generate(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'both');

        if ($type === 'hotspot' || $type === 'both') {
            $this->generatePdf('hotspot');
        }
        if ($type === 'race' || $type === 'both') {
            $this->generatePdf('race');
        }

        return back()->with('success', 'Pool PDF(s) generated.');
    }

    private function generatePdf(string $type): void
    {
        $year = Setting::get('entry_year', (string) date('Y'));
        $amounts = json_decode(Setting::get("pool_{$type}_amounts", $this->defaults["pool_{$type}_amounts"]), true);
        $nomLabel = Setting::get("pool_{$type}_nom", $this->defaults["pool_{$type}_nom"]);
        $footer = Setting::get("pool_{$type}_footer", $this->defaults["pool_{$type}_footer"]);
        $rows = (int) Setting::get('pool_pdf_rows', '15');

        // Calculate example total
        $exampleTotal = 0;
        foreach ($amounts as $amount) {
            $val = (float) preg_replace('/[^0-9.]/', '', str_replace('p', '', $amount));
            if (str_contains($amount, 'p')) $val /= 100;
            $exampleTotal += $val;
        }
        // Add nom
        $nomVal = (float) preg_replace('/[^0-9.]/', '', $nomLabel);
        $exampleTotal += $nomVal;
        $exampleTotal = '£' . number_format($exampleTotal, 2);

        $title = $type === 'hotspot'
            ? 'Pool Entry Form - Hot Spots Only'
            : 'Pool Entry Form';

        $site = [
            'name' => config('olr.site_name'),
            'tagline' => config('olr.tagline'),
            'accent' => config('olr.accent_color', '#2788CF'),
            'primary' => config('olr.primary_color', '#1a2332'),
            'email' => config('olr.contact_email'),
            'phone' => config('olr.contact_phone'),
            'address' => config('olr.address'),
        ];

        // Banner as base64
        $bannerBase64 = null;
        foreach ([config('olr.banner'), '/images/banner.jpeg', '/images/banner.jpg', '/images/banner.png'] as $bannerFile) {
            $bannerPath = public_path(ltrim($bannerFile, '/'));
            if ($bannerFile && file_exists($bannerPath)) {
                $ext = strtolower(pathinfo($bannerPath, PATHINFO_EXTENSION));
                if ($ext === 'jpg') $ext = 'jpeg';
                $bannerBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($bannerPath));
                break;
            }
        }

        $settings = ['year' => $year];

        $pdf = Pdf::loadView('pdf.pool-sheet', compact(
            'settings', 'site', 'bannerBase64', 'amounts', 'nomLabel',
            'footer', 'rows', 'exampleTotal', 'title'
        ));
        $pdf->setPaper('a4');

        File::ensureDirectoryExists(public_path('downloads'));
        $filename = $type === 'hotspot' ? 'pool-hotspots.pdf' : 'pool-races.pdf';
        $pdf->save(public_path('downloads/' . $filename));
    }
}
