@php
    $prizeCategories = \App\Models\PrizeCategory::active()->ordered()->with('positions')->get();
@endphp

@if($prizeCategories->isNotEmpty())
    <div class="mt-10 space-y-8">
        @foreach($prizeCategories as $category)
            @if($category->type === 'positions')
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4" style="background: var(--accent);">
                        <h3 class="text-lg font-bold text-white">{{ $category->name }}</h3>
                    </div>
                    @if($category->positions->isNotEmpty())
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="text-left px-6 py-3 font-semibold text-gray-700">Position</th>
                                    <th class="text-left px-6 py-3 font-semibold text-gray-700">Prize</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->positions as $position)
                                    <tr class="border-b border-gray-100 last:border-0">
                                        <td class="px-6 py-3 text-gray-900 font-medium">{{ $position->label }}</td>
                                        <td class="px-6 py-3 text-gray-700">{{ $position->amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
                        @if($category->positions->first())
                            <p class="text-sm text-gray-500 mt-0.5">{{ $category->positions->first()->label }}</p>
                        @endif
                    </div>
                    @if($category->positions->first())
                        <span class="text-lg font-bold" style="color: var(--accent);">{{ $category->positions->first()->amount }}</span>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@endif
