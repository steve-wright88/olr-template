<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\EntryConfirmation;
use App\Mail\EntryNotification;
use App\Models\Entry;
use App\Models\EntryOffer;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EntryController extends Controller
{
    public function index(): View
    {
        abort_unless(Setting::get('entries_enabled', '1') === '1', 404);
        $settings = [
            'is_open' => Setting::get('entry_is_open', '1'),
            'year' => Setting::get('entry_year', (string) date('Y')),
            'fee' => Setting::get('entry_fee', '150'),
            'currency' => Setting::get('entry_currency', '£'),
            'max_birds' => (int) Setting::get('entry_max_birds', '10'),
            'deadline' => Setting::get('entry_deadline'),
            'notes' => Setting::get('entry_notes'),
        ];

        // Check if deadline has passed
        $deadlinePassed = $settings['deadline'] && now()->gt($settings['deadline']);
        $entriesOpen = $settings['is_open'] === '1' && ! $deadlinePassed;

        $offers = EntryOffer::active()->ordered()->get();

        // Bird acceptance dates
        $acceptanceDates = [
            'start' => Setting::get('bird_acceptance_start'),
            'end' => Setting::get('bird_acceptance_end'),
            'show_on_site' => Setting::get('show_acceptance_dates_site', '1'),
        ];

        return view('site.enter', compact('settings', 'entriesOpen', 'offers', 'acceptanceDates'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Setting::get('entries_enabled', '1') === '1', 404);

        $maxBirds = (int) Setting::get('entry_max_birds', '10');
        $isOpen = Setting::get('entry_is_open', '1') === '1';
        $deadline = Setting::get('entry_deadline');
        $deadlinePassed = $deadline && now()->gt($deadline);

        if (! $isOpen || $deadlinePassed) {
            return back()->with('error', 'Entries are currently closed.');
        }

        $validated = $request->validate([
            'syndicate_name' => 'nullable|string|max:255',
            'flyer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'team_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'offer_id' => 'nullable|integer|exists:entry_offers,id',
            'birds' => 'required|array|min:1|max:' . $maxBirds,
            'birds.*.ring_number' => 'required|string|max:50',
            'birds.*.pigeon_name' => 'nullable|string|max:100',
        ], [
            'birds.required' => 'Please add at least one bird.',
            'birds.*.ring_number.required' => 'Ring number is required for each bird.',
            'flyer_name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
        ]);

        // Calculate fee
        $offerId = null;
        $totalFee = null;
        $entryFee = (float) Setting::get('entry_fee', '150');

        if (! empty($validated['offer_id'])) {
            $offer = EntryOffer::where('id', $validated['offer_id'])->active()->first();
            if ($offer) {
                $offerId = $offer->id;
                $totalFee = $offer->price;
            }
        }

        if ($offerId === null) {
            $totalFee = count($validated['birds']) * $entryFee;
        }

        $entry = DB::transaction(function () use ($validated, $offerId, $totalFee) {
            $entry = Entry::create([
                'reference' => Entry::generateReference(),
                'syndicate_name' => $validated['syndicate_name'] ?? null,
                'flyer_name' => $validated['flyer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'team_name' => $validated['team_name'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'number_of_birds' => count($validated['birds']),
                'status' => 'pending',
                'season_year' => Setting::get('entry_year', (string) date('Y')),
                'offer_id' => $offerId,
                'total_fee' => $totalFee,
            ]);

            foreach ($validated['birds'] as $bird) {
                $entry->birds()->create([
                    'ring_number' => $bird['ring_number'],
                    'pigeon_name' => $bird['pigeon_name'] ?? null,
                ]);
            }

            return $entry;
        });

        // Send emails (synchronous for shared hosting compatibility)
        try {
            Mail::to($entry->email)->send(new EntryConfirmation($entry->load('birds')));

            $adminEmail = config('olr.contact_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new EntryNotification($entry));
            }
        } catch (\Exception $e) {
            // Don't fail the entry if email fails
            logger()->error('Entry email failed: ' . $e->getMessage());
        }

        return redirect()->route('enter')
            ->with('success', 'Entry submitted successfully! Your reference is ' . $entry->reference . '. A confirmation email has been sent to ' . $entry->email . '.');
    }
}
