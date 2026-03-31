<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #333; }

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
        .header-simple {
            background: {{ $site['primary'] }};
            padding: 20px 30px 16px;
            text-align: center;
        }
        .header-simple h1 {
            font-size: 18pt;
            font-weight: 900;
            color: white;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header-simple .tagline {
            font-size: 6.5pt;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 3px;
        }

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

        .content { padding: 14px 30px 10px; }

        /* Fields */
        .fields-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .fields-table td {
            padding: 0 0 6px 0;
            border: none;
            background: none;
            vertical-align: bottom;
        }
        .fields-table .field-label {
            font-size: 8pt;
            font-weight: 700;
            color: #555;
            width: 100px;
            padding-right: 8px;
        }
        .fields-table .field-line {
            border-bottom: 1px solid #bbb;
            height: 20px;
        }

        /* Pool table */
        .pool-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .pool-table th {
            background: {{ $site['primary'] }};
            color: white;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 5px 4px;
            text-align: center;
            border: 1px solid {{ $site['primary'] }};
        }
        .pool-table th:first-child {
            text-align: left;
            padding-left: 8px;
            width: 120px;
        }
        .pool-table th:last-child {
            width: 55px;
        }
        .pool-table td {
            padding: 0 4px;
            font-size: 8pt;
            height: 20px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .pool-table td:first-child {
            text-align: left;
            padding-left: 8px;
            font-family: 'Courier', monospace;
            font-size: 7.5pt;
        }
        .pool-table td:last-child {
            font-weight: 700;
        }
        .pool-table tr:nth-child(even) td { background: #f9fafb; }

        /* Example row */
        .pool-table .example-label {
            font-size: 6.5pt;
            font-weight: 800;
            color: #c8102e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .pool-table .example-ring {
            font-family: 'Courier', monospace;
            font-size: 7.5pt;
            color: #666;
        }

        /* Footer */
        .disclaimer {
            margin-top: 14px;
            text-align: center;
            font-size: 7.5pt;
            font-weight: 700;
            color: #333;
            line-height: 1.5;
        }

        .payment-info {
            margin-top: 14px;
            font-size: 7.5pt;
            font-weight: 700;
            color: #333;
            line-height: 1.5;
        }

        .pdf-footer {
            margin-top: 10px;
            padding-top: 6px;
            border-top: 2px solid {{ $site['primary'] }};
            text-align: center;
            font-size: 6.5pt;
            color: #aaa;
            line-height: 1.4;
        }
        .pdf-footer strong { color: {{ $site['primary'] }}; font-size: 7pt; }
    </style>
</head>
<body>
    {{-- Banner --}}
    @if($bannerBase64)
        <div class="banner">
            <img src="{{ $bannerBase64 }}" class="banner-img" alt="">
        </div>
    @else
        <div class="header-simple">
            <h1>{{ $site['name'] }}</h1>
            <div class="tagline">{{ $site['tagline'] }}</div>
        </div>
    @endif

    <div class="year-banner">{{ $settings['year'] }} {{ $title }}</div>

    <div class="content">
        {{-- Fields --}}
        <table class="fields-table">
            <tr>
                <td class="field-label">Race Point</td>
                <td class="field-line"></td>
            </tr>
            <tr>
                <td class="field-label">Date</td>
                <td class="field-line"></td>
            </tr>
            <tr>
                <td class="field-label">Syndicate Name</td>
                <td class="field-line"></td>
            </tr>
        </table>

        {{-- Pool Table --}}
        <table class="pool-table">
            <thead>
                <tr>
                    <th>Ring Number</th>
                    @foreach($amounts as $amount)
                        <th>{{ $amount }}</th>
                    @endforeach
                    <th>{{ $nomLabel }}</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                {{-- Example row --}}
                <tr>
                    <td>
                        <span class="example-label">EXAMPLE</span>
                        <span class="example-ring">GB20S12345</span>
                    </td>
                    @foreach($amounts as $amount)
                        <td>x</td>
                    @endforeach
                    <td>x</td>
                    <td>{{ $exampleTotal }}</td>
                </tr>
                {{-- Blank rows --}}
                @for($i = 0; $i < $rows; $i++)
                    <tr>
                        <td></td>
                        @foreach($amounts as $amount)
                            <td></td>
                        @endforeach
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        {{-- Disclaimer --}}
        <div class="disclaimer">
            {!! nl2br(e($footer)) !!}
            <br>G.Tomlinson ............... One Loft Race Manager
        </div>

        {{-- Payment info --}}
        <div class="payment-info">
            Make Cheques Payable To WDW Shop Ltd and send to:<br>
            G.Tomlinson {{ $site['address'] }}
        </div>

        {{-- Footer --}}
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
