<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6; margin: 0; padding: 0; background: #f5f5f5; }
        .wrapper { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: {{ config('olr.accent_color', '#2788CF') }}; color: white; padding: 15px 30px; }
        .header h1 { margin: 0; font-size: 16px; }
        .content { padding: 25px 30px; }
        .detail { margin-bottom: 5px; }
        .detail strong { display: inline-block; width: 100px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { text-align: left; padding: 6px 8px; font-size: 11px; color: #666; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 13px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>New Entry: {{ $entry->reference }}</h1>
        </div>
        <div class="content">
            <p>A new entry has been submitted for the {{ $entry->season_year }} season.</p>

            <div class="detail"><strong>Reference:</strong> {{ $entry->reference }}</div>
            <div class="detail"><strong>Flyer:</strong> {{ $entry->flyer_name }}</div>
            <div class="detail"><strong>Email:</strong> {{ $entry->email }}</div>
            <div class="detail"><strong>Phone:</strong> {{ $entry->phone ?: '-' }}</div>
            <div class="detail"><strong>Syndicate:</strong> {{ $entry->syndicate_name ?: '-' }}</div>
            <div class="detail"><strong>Team:</strong> {{ $entry->team_name ?: '-' }}</div>
            <div class="detail"><strong>Birds:</strong> {{ $entry->number_of_birds }}</div>

            @if($entry->notes)
                <p><strong>Notes:</strong> {{ $entry->notes }}</p>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ring Number</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entry->birds as $i => $bird)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $bird->ring_number }}</strong></td>
                            <td>{{ $bird->pigeon_name ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
