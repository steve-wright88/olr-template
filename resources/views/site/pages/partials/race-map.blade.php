@php
    $loftLat = \App\Models\Setting::get('race_map_loft_lat', '53.05');
    $loftLng = \App\Models\Setting::get('race_map_loft_lng', '-1.48');
    $mapPoints = json_decode(\App\Models\Setting::get('race_map_points', '[]'), true) ?: [];
@endphp

@if(count($mapPoints) > 0)
<div class="mb-10 rounded-lg overflow-hidden border border-gray-200 shadow-sm" style="position: relative; z-index: 0;">
    <div id="race-map" style="height: 480px; width: 100%;"></div>
</div>

{{-- Map Key --}}
@php
    $colors = ['#2563eb','#0891b2','#059669','#d97706','#ea580c','#dc2626','#7c3aed','#db2777'];
    $typeColors = ['final' => '#dc2626', 'super' => '#7c3aed'];
@endphp
<div class="mb-10 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm">
    <span class="flex items-center gap-2">
        <span class="inline-block w-4 h-4 rounded-full" style="background: var(--primary);"></span>
        <span class="font-medium text-gray-700">Loft</span>
    </span>
    @foreach($mapPoints as $i => $point)
        @php
            $color = !empty($point['color']) ? $point['color'] : ($typeColors[$point['type'] ?? 'hotspot'] ?? $colors[$i % count($colors)]);
            $label = $point['name'] ?? ('Point ' . ($i + 1));
        @endphp
        <span class="flex items-center gap-2">
            <span class="inline-block rounded" style="background: {{ $color }}; width: 20px; height: 3px;"></span>
            <span class="font-medium text-gray-700">{{ $label }}</span>
        </span>
    @endforeach
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<style>
    #race-map .leaflet-pane,
    #race-map .leaflet-control {
        z-index: 1 !important;
    }
    #race-map .leaflet-top {
        z-index: 2 !important;
    }
    .race-marker {
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 11px;
        font-family: 'Inter', sans-serif;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    .race-marker.loft-marker {
        background: var(--primary);
        width: 32px;
        height: 32px;
        font-size: 13px;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
    }
    .leaflet-popup-content {
        margin: 10px 14px;
        font-size: 13px;
        line-height: 1.5;
    }
    .leaflet-popup-content strong {
        display: block;
        font-size: 14px;
        margin-bottom: 2px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var loft = { lat: {{ $loftLat }}, lng: {{ $loftLng }} };
    var colors = ['#2563eb','#0891b2','#059669','#d97706','#ea580c','#dc2626','#7c3aed','#db2777'];
    var typeColors = { final: '#dc2626', super: '#7c3aed' };
    var typeLabels = { hotspot: 'Hot Spot', final: 'Grand Final', super: 'Super Final' };

    var races = @json($mapPoints).map(function(p, i) {
        var type = p.type || 'hotspot';
        return {
            name: p.name,
            lat: parseFloat(p.lat),
            lng: parseFloat(p.lng),
            distance: p.distance || '',
            date: p.date || '',
            type: type,
            customColor: p.color || '',
            color: p.color || typeColors[type] || colors[i % colors.length],
            label: type === 'final' ? 'F' : type === 'super' ? 'SF' : String(i + 1)
        };
    });

    var map = L.map('race-map', { scrollWheelZoom: false });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Lines from loft to each point
    races.forEach(function(r) {
        if (!isNaN(r.lat) && !isNaN(r.lng)) {
            L.polyline([[loft.lat, loft.lng], [r.lat, r.lng]], {
                color: r.color,
                weight: 3,
                opacity: 0.8
            }).addTo(map);
        }
    });

    // Loft marker
    var loftIcon = L.divIcon({
        className: '',
        html: '<div class="race-marker loft-marker"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div>',
        iconSize: [32, 32],
        iconAnchor: [16, 16],
        popupAnchor: [0, -18]
    });
    L.marker([loft.lat, loft.lng], { icon: loftIcon })
        .addTo(map)
        .bindPopup('<strong>Loft</strong>Race HQ');

    // Race markers
    races.forEach(function(r) {
        if (isNaN(r.lat) || isNaN(r.lng)) return;

        var icon = L.divIcon({
            className: '',
            html: '<div class="race-marker" style="background:' + r.color + '">' + r.label + '</div>',
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            popupAnchor: [0, -16]
        });

        var popup = '<strong>' + r.name + '</strong>';
        popup += typeLabels[r.type] || r.type;
        if (r.distance) popup += '<br><span style="color:' + r.color + ';font-weight:600">' + r.distance + '</span>';
        if (r.date) popup += '<br>' + r.date;

        L.marker([r.lat, r.lng], { icon: icon })
            .addTo(map)
            .bindPopup(popup);
    });

    // Fit bounds
    var allPoints = [[loft.lat, loft.lng]];
    races.forEach(function(r) {
        if (!isNaN(r.lat) && !isNaN(r.lng)) allPoints.push([r.lat, r.lng]);
    });
    map.fitBounds(L.latLngBounds(allPoints), { padding: [30, 30] });
});
</script>
@endif
