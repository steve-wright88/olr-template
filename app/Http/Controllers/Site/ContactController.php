<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $to = Setting::get('contact_email');

        if ($to) {
            Mail::raw(
                "Name: {$validated['name']}\nEmail: {$validated['email']}\n\n{$validated['message']}",
                function ($msg) use ($to, $validated) {
                    $msg->to($to)
                        ->replyTo($validated['email'], $validated['name'])
                        ->subject('[Website] ' . $validated['subject']);
                }
            );
        }

        return back()->with('contact_sent', true);
    }
}
