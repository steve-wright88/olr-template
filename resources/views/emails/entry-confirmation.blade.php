<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6; margin: 0; padding: 0; background: #f5f5f5; }
        .wrapper { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: {{ config('olr.primary_color', '#1a2332') }}; color: white; padding: 20px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; }
        .content { padding: 30px; }
        .reference { background: #f0f9ff; border: 1px solid #bae6fd; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 25px; }
        .reference .code { font-size: 24px; font-weight: bold; color: {{ config('olr.accent_color', '#2788CF') }}; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { text-align: left; padding: 8px; font-size: 12px; color: #666; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .footer { padding: 20px 30px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('olr.site_name') }}</h1>
        </div>
        <div class="content">
            <p>Dear {{ $entry->flyer_name }},</p>
            <p>Thank you for entering the {{ config('olr.site_name') }} {{ $entry->season_year }} season. Your entry has been received and is being processed.</p>

            <div class="reference">
                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Your Reference Number</div>
                <div class="code">{{ $entry->reference }}</div>
            </div>

            <h3>Entry Details</h3>
            <p><strong>Name:</strong> {{ $entry->flyer_name }}<br>
            @if($entry->syndicate_name)<strong>Syndicate:</strong> {{ $entry->syndicate_name }}<br>@endif
            @if($entry->team_name)<strong>Team:</strong> {{ $entry->team_name }}<br>@endif
            <strong>Birds:</strong> {{ $entry->number_of_birds }}</p>

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

            <p><strong>Total fee:</strong> {{ \App\Models\Setting::get('entry_currency', '£') }}{{ number_format($entry->number_of_birds * (int) \App\Models\Setting::get('entry_fee', '150')) }}</p>

            <p>Please arrange payment at your earliest convenience. If you have any questions, please reply to this email or contact us.</p>

            <p>Good luck!</p>
        </div>
        <div class="footer">
            <p>{{ config('olr.site_name') }} | {{ config('olr.tagline') }}</p>
            @if(config('olr.contact_email'))
                <p>{{ config('olr.contact_email') }}</p>
            @endif
        </div>
    </div>
</body>
</html>
