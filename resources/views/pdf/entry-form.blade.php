<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #333; }

        /* Banner header with background image */
        .banner {
            width: 100%;
            height: 220px;
            background: {{ $site['primary'] }};
            overflow: hidden;
        }
        .banner-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        /* Fallback header when no banner image */
        .header-simple {
            background: {{ $site['primary'] }};
            padding: 16px 30px 12px;
            text-align: center;
        }
        .header-simple .logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            margin-bottom: 4px;
        }
        .header-simple h1 {
            font-size: 18pt;
            font-weight: 900;
            color: white;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        .header-simple .tagline {
            font-size: 6.5pt;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        /* Year banner */
        .year-banner {
            background: {{ $site['accent'] }};
            color: white;
            padding: 6px 30px;
            font-size: 10pt;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Content */
        .content { padding: 12px 30px 10px; }

        /* Key info strip */
        .key-info {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid {{ $site['accent'] }};
            margin-bottom: 10px;
        }
        .key-info td {
            padding: 6px 10px;
            text-align: center;
            border: none;
            background: white;
        }
        .key-info td + td { border-left: 1px solid #e0e0e0; }
        .key-info-label {
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 1px;
        }
        .key-info-value {
            font-size: 12pt;
            font-weight: 800;
            color: {{ $site['primary'] }};
        }
        .key-info-value .currency { color: {{ $site['accent'] }}; }

        /* Notes box */
        .notes {
            background: #f8f9fa;
            border-left: 3px solid {{ $site['accent'] }};
            padding: 6px 10px;
            margin-bottom: 8px;
            font-size: 7pt;
            line-height: 1.4;
            color: #555;
        }

        /* Section heading */
        .section-title {
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: {{ $site['accent'] }};
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        /* Form fields */
        .fields-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .fields-table td {
            width: 50%;
            padding: 0 6px 4px 0;
            border: none;
            background: none;
            vertical-align: top;
        }
        .fields-table td:last-child { padding-right: 0; }
        .field label {
            display: block;
            font-size: 6.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            margin-bottom: 2px;
        }
        .field .input-line {
            border: 1px solid #bbb;
            height: 18px;
            background: #fafafa;
        }

        /* Bird table */
        .bird-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }
        .bird-table th {
            background: {{ $site['primary'] }};
            color: white;
            font-size: 6pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 6px;
            text-align: left;
        }
        .bird-table th:first-child { width: 22px; text-align: center; }
        .bird-table td {
            padding: 0 6px;
            font-size: 7.5pt;
            height: 18px;
            border-bottom: 1px solid #e8e8e8;
        }
        .bird-table td:first-child {
            text-align: center;
            color: #bbb;
            font-size: 6.5pt;
            font-weight: 600;
        }
        .bird-table tr:nth-child(even) td { background: #f9fafb; }

        /* Footer */
        .pdf-footer {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 2px solid {{ $site['primary'] }};
            text-align: center;
            font-size: 6.5pt;
            color: #aaa;
            line-height: 1.4;
        }
        .pdf-footer strong { color: {{ $site['primary'] }}; font-size: 7pt; }
        .pdf-footer .website { color: {{ $site['accent'] }}; }
    </style>
</head>
<body>
    {{-- Header with banner image or fallback --}}
    @if($bannerBase64)
        <div class="banner">
            <img src="{{ $bannerBase64 }}" class="banner-img" alt="">
        </div>
    @else
        <div class="header-simple">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="logo" alt="">
            @endif
            <h1>{{ $site['name'] }}</h1>
            <div class="tagline">{{ $site['tagline'] }}</div>
        </div>
    @endif

    <div class="year-banner">{{ $settings['year'] }} Official Entry Form</div>

    <div class="content">
        {{-- Key info strip - dynamic columns based on what's set --}}
        @php
            $infoCols = [];
            if ($fields['entry_fee'] ?? true) {
                $infoCols[] = ['label' => 'Entry Fee Per Bird', 'value' => '<span class="currency">' . $settings['currency'] . '</span>' . $settings['fee']];
            }
            if ($settings['deadline']) {
                $infoCols[] = ['label' => 'Entry Deadline', 'value' => \Carbon\Carbon::parse($settings['deadline'])->format('j M Y')];
            }
            if (($fields['acceptance_dates'] ?? true) && $settings['acceptance_start'] && ($settings['show_acceptance_pdf'] ?? false)) {
                $start = \Carbon\Carbon::parse($settings['acceptance_start'])->format('j M');
                $end = $settings['acceptance_end'] ? \Carbon\Carbon::parse($settings['acceptance_end'])->format('j M Y') : 'TBC';
                $infoCols[] = ['label' => 'Birds Accepted', 'value' => $start . ' - ' . $end];
            }
        @endphp
        @if(count($infoCols))
            <table class="key-info">
                <tr>
                    @foreach($infoCols as $col)
                        <td style="width: {{ 100 / count($infoCols) }}%;">
                            <div class="key-info-label">{{ $col['label'] }}</div>
                            <div class="key-info-value">{!! $col['value'] !!}</div>
                        </td>
                    @endforeach
                </tr>
            </table>
        @endif

        {{-- Offers --}}
        @if($offers->isNotEmpty())
            <div style="margin-bottom: 8px;">
                <div class="section-title">Entry Packages</div>
                <table style="width: 100%; border-collapse: collapse; margin-top: 3px;">
                    <tr style="background: {{ $site['primary'] }};">
                        <td style="color: white; font-size: 6.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 8px;">Package</td>
                        <td style="color: white; font-size: 6.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 8px; text-align: center;">Birds</td>
                        <td style="color: white; font-size: 6.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 8px; text-align: center;">Bonus</td>
                        <td style="color: white; font-size: 6.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 8px; text-align: right;">Price</td>
                    </tr>
                    @foreach($offers as $offer)
                        <tr>
                            <td style="padding: 4px 8px; font-size: 8pt; font-weight: 600; border-bottom: 1px solid #e8e8e8;">{{ $offer->name }}</td>
                            <td style="padding: 4px 8px; font-size: 8pt; text-align: center; border-bottom: 1px solid #e8e8e8;">{{ $offer->number_of_birds }}</td>
                            <td style="padding: 4px 8px; font-size: 8pt; text-align: center; border-bottom: 1px solid #e8e8e8; color: {{ $offer->bonus_birds > 0 ? '#16a34a' : '#999' }};">{{ $offer->bonus_birds > 0 ? '+' . $offer->bonus_birds . ' FREE' : '-' }}</td>
                            <td style="padding: 4px 8px; font-size: 8pt; text-align: right; font-weight: 700; border-bottom: 1px solid #e8e8e8; color: {{ $site['accent'] }};">{{ $settings['currency'] }}{{ number_format($offer->price, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
                <p style="font-size: 6.5pt; color: #999; margin-top: 3px;">Or enter at {{ $settings['currency'] }}{{ $settings['fee'] }} per bird without a package.</p>
            </div>
        @endif

        @if($settings['notes'])
            <div class="notes">{!! nl2br(e($settings['notes'])) !!}</div>
        @endif

        {{-- Owner Details - only show enabled fields --}}
        <div class="section-title">Owner Details</div>

        <table class="fields-table">
            @if(($fields['syndicate_name'] ?? true) || ($fields['flyer_name'] ?? true))
                <tr>
                    @if($fields['syndicate_name'] ?? true)
                        <td><div class="field"><label>Syndicate Name</label><div class="input-line"></div></div></td>
                    @endif
                    @if($fields['flyer_name'] ?? true)
                        <td><div class="field"><label>Flyer's Name</label><div class="input-line"></div></div></td>
                    @endif
                </tr>
            @endif
            @if(($fields['email'] ?? true) || ($fields['phone'] ?? true))
                <tr>
                    @if($fields['email'] ?? true)
                        <td><div class="field"><label>Email Address</label><div class="input-line"></div></div></td>
                    @endif
                    @if($fields['phone'] ?? true)
                        <td><div class="field"><label>Mobile / Phone</label><div class="input-line"></div></div></td>
                    @endif
                </tr>
            @endif
            @if(($fields['address'] ?? false) || ($fields['country'] ?? false))
                <tr>
                    @if($fields['address'] ?? false)
                        <td><div class="field"><label>Address</label><div class="input-line"></div></div></td>
                    @endif
                    @if($fields['country'] ?? false)
                        <td><div class="field"><label>Country</label><div class="input-line"></div></div></td>
                    @endif
                </tr>
            @endif
            @if(($fields['number_of_birds'] ?? true) || ($fields['team_name'] ?? true))
                <tr>
                    @if($fields['number_of_birds'] ?? true)
                        <td><div class="field"><label>Number of Birds</label><div class="input-line"></div></div></td>
                    @endif
                    @if($fields['team_name'] ?? true)
                        <td><div class="field"><label>Team Name</label><div class="input-line"></div></div></td>
                    @endif
                </tr>
            @endif
        </table>

        {{-- Bird Details --}}
        <div class="section-title">Bird Details</div>

        <table class="bird-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ring Number</th>
                    @if($fields['pigeon_name'] ?? true)
                        <th>Pigeon Name</th>
                    @endif
                    @if($fields['pigeon_sex'] ?? false)
                        <th>Sex</th>
                    @endif
                    @if($fields['pigeon_colour'] ?? false)
                        <th>Colour</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= $settings['max_birds']; $i++)
                    <tr>
                        <td>{{ $i }}</td>
                        <td></td>
                        @if($fields['pigeon_name'] ?? true)
                            <td></td>
                        @endif
                        @if($fields['pigeon_sex'] ?? false)
                            <td></td>
                        @endif
                        @if($fields['pigeon_colour'] ?? false)
                            <td></td>
                        @endif
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="pdf-footer">
            <p><strong>{{ $site['name'] }}</strong> | {{ $site['address'] }}</p>
            <p>
                @if($site['email']){{ $site['email'] }}@endif
                @if($site['email'] && $site['phone']) | @endif
                @if($site['phone']){{ $site['phone'] }}@endif
            </p>
        </div>
    </div>
</body>
</html>
