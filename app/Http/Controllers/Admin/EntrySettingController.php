<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class EntrySettingController extends Controller
{
    protected array $pdfFieldDefaults = [
        'syndicate_name' => true,
        'flyer_name' => true,
        'email' => true,
        'phone' => true,
        'address' => false,
        'country' => false,
        'number_of_birds' => true,
        'team_name' => true,
        'entry_fee' => true,
        'acceptance_dates' => true,
        'pigeon_name' => true,
        'pigeon_sex' => false,
        'pigeon_colour' => false,
    ];

    public function index(): View
    {
        $settings = [
            'entry_year' => Setting::get('entry_year', (string) date('Y')),
            'entry_fee' => Setting::get('entry_fee', '150'),
            'entry_currency' => Setting::get('entry_currency', '£'),
            'entry_max_birds' => Setting::get('entry_max_birds', '10'),
            'entry_deadline' => Setting::get('entry_deadline'),
            'entry_is_open' => Setting::get('entry_is_open', '1'),
            'entry_notes' => Setting::get('entry_notes'),
            'entry_pdf_intro' => Setting::get('entry_pdf_intro'),
            'bird_acceptance_start' => Setting::get('bird_acceptance_start'),
            'bird_acceptance_end' => Setting::get('bird_acceptance_end'),
            'show_acceptance_dates_site' => Setting::get('show_acceptance_dates_site', '1'),
            'show_acceptance_dates_pdf' => Setting::get('show_acceptance_dates_pdf', '1'),
        ];

        $pdfExists = file_exists(public_path('downloads/entry-form.pdf'));

        return view('admin.entry-settings', compact('settings', 'pdfExists'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_year' => 'required|string|max:10',
            'entry_fee' => 'required|string|max:20',
            'entry_currency' => 'required|string|max:5',
            'entry_max_birds' => 'required|integer|min:1|max:50',
            'entry_deadline' => 'nullable|date',
            'entry_is_open' => 'boolean',
            'entry_notes' => 'nullable|string|max:5000',
            'entry_pdf_intro' => 'nullable|string|max:5000',
            'bird_acceptance_start' => 'nullable|date',
            'bird_acceptance_end' => 'nullable|date',
            'show_acceptance_dates_site' => 'boolean',
            'show_acceptance_dates_pdf' => 'boolean',
        ]);

        $validated['entry_is_open'] = $request->boolean('entry_is_open') ? '1' : '0';
        $validated['show_acceptance_dates_site'] = $request->boolean('show_acceptance_dates_site') ? '1' : '0';
        $validated['show_acceptance_dates_pdf'] = $request->boolean('show_acceptance_dates_pdf') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Entry settings saved.');
    }

    public function pdf(): View
    {
        $settings = [
            'entry_year' => Setting::get('entry_year', (string) date('Y')),
            'entry_fee' => Setting::get('entry_fee', '150'),
            'entry_currency' => Setting::get('entry_currency', '£'),
            'entry_max_birds' => Setting::get('entry_max_birds', '10'),
            'entry_deadline' => Setting::get('entry_deadline'),
            'entry_pdf_intro' => Setting::get('entry_pdf_intro'),
            'bird_acceptance_start' => Setting::get('bird_acceptance_start'),
            'bird_acceptance_end' => Setting::get('bird_acceptance_end'),
            'show_acceptance_dates_pdf' => Setting::get('show_acceptance_dates_pdf', '1'),
        ];

        $pdfFields = json_decode(Setting::get('entry_pdf_fields', '{}'), true);
        $fields = array_merge($this->pdfFieldDefaults, $pdfFields);

        $pdfExists = file_exists(public_path('downloads/entry-form.pdf'));

        return view('admin.entry-pdf', compact('settings', 'pdfExists', 'fields'));
    }

    public function updatePdf(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_year' => 'required|string|max:10',
            'entry_fee' => 'required|string|max:20',
            'entry_currency' => 'required|string|max:5',
            'entry_max_birds' => 'required|integer|min:1|max:50',
            'entry_deadline' => 'nullable|date',
            'entry_pdf_intro' => 'nullable|string|max:5000',
            'pdf_fields' => 'nullable|array',
            'pdf_fields.*' => 'boolean',
        ]);

        // Save non-field settings
        foreach (['entry_year', 'entry_fee', 'entry_currency', 'entry_max_birds', 'entry_deadline', 'entry_pdf_intro'] as $key) {
            Setting::set($key, $validated[$key] ?? null);
        }

        // Save PDF field toggles
        $fieldConfig = [];
        foreach ($this->pdfFieldDefaults as $field => $default) {
            $fieldConfig[$field] = $request->boolean("pdf_fields.{$field}");
        }
        Setting::set('entry_pdf_fields', json_encode($fieldConfig));

        if ($request->input('action') === 'generate') {
            $this->generatePdf();
            return back()->with('success', 'Settings saved and PDF generated.');
        }

        return back()->with('success', 'PDF settings saved.');
    }

    public function generatePdf(): RedirectResponse
    {
        $settings = [
            'year' => Setting::get('entry_year', (string) date('Y')),
            'fee' => Setting::get('entry_fee', '150'),
            'currency' => Setting::get('entry_currency', '£'),
            'max_birds' => (int) Setting::get('entry_max_birds', '10'),
            'deadline' => Setting::get('entry_deadline'),
            'notes' => Setting::get('entry_pdf_intro'),
            'acceptance_start' => Setting::get('bird_acceptance_start'),
            'acceptance_end' => Setting::get('bird_acceptance_end'),
            'show_acceptance_pdf' => Setting::get('show_acceptance_dates_pdf', '1') === '1',
        ];

        $site = [
            'name' => config('olr.site_name'),
            'tagline' => config('olr.tagline'),
            'accent' => config('olr.accent_color', '#2788CF'),
            'primary' => config('olr.primary_color', '#1a2332'),
            'email' => config('olr.contact_email'),
            'phone' => config('olr.contact_phone'),
            'address' => config('olr.address'),
        ];

        // Logo as base64 - try multiple paths
        $logoBase64 = null;
        foreach ([config('olr.logo'), '/images/logo.jpg', '/images/logo.webp'] as $logoFile) {
            $logoPath = public_path(ltrim($logoFile, '/'));
            if ($logoFile && file_exists($logoPath)) {
                $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                if ($ext === 'jpg') $ext = 'jpeg';
                $logoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($logoPath));
                break;
            }
        }

        // Banner as base64 - try multiple paths
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

        // PDF field toggles
        $pdfFields = json_decode(Setting::get('entry_pdf_fields', '{}'), true);
        $fields = array_merge($this->pdfFieldDefaults, $pdfFields);

        // Active offers
        $offers = \App\Models\EntryOffer::active()->ordered()->get();

        $pdf = Pdf::loadView('pdf.entry-form', compact('settings', 'site', 'logoBase64', 'bannerBase64', 'fields', 'offers'));
        $pdf->setPaper('a4');

        File::ensureDirectoryExists(public_path('downloads'));
        $pdf->save(public_path('downloads/entry-form.pdf'));

        return back()->with('success', 'Entry form PDF generated and saved to downloads.');
    }
}
