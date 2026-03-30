<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(): View
    {
        $agents = Agent::ordered()->get();

        return view('admin.agents.index', compact('agents'));
    }

    public function create(): View
    {
        return view('admin.agents.form', ['agent' => new Agent]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('agents', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? Agent::max('sort_order') + 1;

        Agent::create($validated);

        return redirect()->route('admin.agents.index')->with('success', 'Agent added.');
    }

    public function edit(Agent $agent): View
    {
        return view('admin.agents.form', compact('agent'));
    }

    public function update(Request $request, Agent $agent): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('agents', 'public');
        } else {
            unset($validated['photo']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $agent->update($validated);

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated.');
    }

    public function destroy(Agent $agent): RedirectResponse
    {
        $agent->delete();

        return redirect()->route('admin.agents.index')->with('success', 'Agent removed.');
    }
}
