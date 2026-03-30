@extends('layouts.admin')

@section('title', 'Agents')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Agents</h1>
        <a href="{{ route('admin.agents.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
           style="background:var(--accent);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Agent
        </a>
    </div>

    @if($agents->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg px-6 py-10 text-center">
            <p class="text-gray-500">No agents yet. Add your first agent to get started.</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Agent</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Country / Region</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Contact</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($agents as $agent)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($agent->photo)
                                        <img src="{{ asset('images/' . $agent->photo) }}" alt="{{ $agent->name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $agent->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $agent->country }}
                                @if($agent->region)
                                    <span class="text-gray-400">/ {{ $agent->region }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($agent->email) <div>{{ $agent->email }}</div> @endif
                                @if($agent->phone) <div>{{ $agent->phone }}</div> @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($agent->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.agents.edit', $agent) }}" class="text-gray-400 hover:text-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" class="inline"
                                          onsubmit="return confirm('Remove this agent?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
