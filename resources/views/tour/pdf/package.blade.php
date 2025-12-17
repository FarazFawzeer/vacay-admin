<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $package->heading ?? 'Tour Package' }}</title>
    <style>
        /* General Reset and Font for PDF */
        @page {
            /* Decreasing this pulls the border closer to the paper edge */
            margin: 0.2in;
        }

        .page-border {
            position: fixed;
            /* Setting these to a negative value relative to the page margin
       brings the border even closer to the physical edge */
            top: -0.1in;
            left: -0.1in;

            /* We adjust the width/height to account for the negative offset */
            width: calc(100% + 0.2in);
            height: calc(100% + 0.2in);

            border: 1px solid #313b5e;
            box-sizing: border-box;
            z-index: -1000;
            pointer-events: none;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            /* Recommended font for Dompdf to handle various characters */
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #333;
        }

        /* Layout Tables */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .layout-table td {
            vertical-align: top;
            padding: 0;
        }

        /* Day Number Styling */
        .day-number-cell {
            width: 60px;
            font-size: 30pt;
            font-weight: bold;
            color: #313b5e;
            line-height: 1;
        }

        /* Accommodation Row Styling */
        .accommodation-table {
            width: 100%;
            border-top: 2px solid #313b5e;
            margin-top: 20px;
            padding-top: 10px;
        }

        .accommodation-table td {
            width: 33.33%;
            text-align: center;
            padding: 10px 5px;
        }

        /* Ensure images don't stretch */
        .itinerary-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            display: block;
            margin: 15px 0;
            border-radius: 6px;
        }

        .content-wrapper {
            margin: 0 auto;
            width: 100%;
            padding: 0;
        }

        /* --- Header Styles --- */
        .package-header {
            position: relative;
            width: 100%;
            padding: 20px 10px;
            margin-bottom: 20px;
        }

        .package-header h1 {
            text-align: center;
            color: #313b5e;
            margin-top: 60px;
            font-size: 24px;
            font-weight: bold;
        }

        .sri-lanka-badge {
            font-size: 0.5rem;
            color: #000;
            text-align: center;
            font-weight: 600;
        }

        /* Route Display */
        .route-display {
            display: inline-block;
            /* Forces background to wrap tightly around text */
            text-align: center;
            margin: 20px auto;
            /* 'auto' centers the inline-block element */
            padding: 10px 20px;
            /* Added more horizontal padding for better look */
            background-color: #f8f8f8;
            border-radius: 5px;
            border: 1px solid #eee;
            /* Optional: adds definition */
        }

        .route-label {
            font-weight: bold;
            color: #96c93e;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .route-path {
            display: inline-block;
            white-space: nowrap;
            /* Keep the route on a single line if possible */
        }

        .route-point {
            display: inline-block;
            margin: 0 5px;
            padding: 2px 8px;
            font-size: 9pt;
            vertical-align: middle;
        }

        .route-point img {
            vertical-align: text-bottom;
        }

        .route-arrow {
            display: inline-block;
            color: #313b5e;
            font-weight: bold;
            margin: 0 3px;
            vertical-align: middle;
        }

        .hero-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        /* Description */
        .description-section {
            margin-bottom: 20px;
            line-height: 1.5;
            padding: 0 10px;
            text-align: justify;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* --- Summary Section --- */
        .pdf-center-wrapper {
            text-align: center;
            /* Center the card */
            margin: 0 10px;
        }

        .pdf-page-container {
            width: 100%;
            height: 1050px;
            /* Approximate height of an A4 page */
            display: table;
        }

        /* Cell that handles vertical centering */
        .pdf-vertical-center {
            display: table-cell;
            vertical-align: middle;
            width: 100%;
        }

        .tour-summary-card {
            page-break-inside: avoid;
            width: 90%;
            margin: 0 auto;
            padding: 30px;
            border-radius: 16px;
            /* Optional: for card look */
            background-color: #e8f4ff;
        }

        /* Force vertical centering in PDF */
        .pdf-full-page-wrapper {
            display: table;
            width: 100%;
            height: 100%;
            /* This might need to be a fixed height like 900px if your PDF engine is older */
            min-height: 100vh;
        }

        .pdf-middle-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            /* Centers horizontal content */
        }

        /* Align the text back to left inside the centered card */
        .tour-summary-card {
            text-align: left;
        }

        .tour-summary-card h2 {
            color: #313b5e;
            font-size: 16pt;
            margin-bottom: 10px;
            text-align: center;
        }

        .summary-description {
            line-height: 1.5;
            margin-bottom: 20px;
            text-align: justify;
        }

        .section-title {
            color: #4a5568;
            font-size: 12pt;
            border-bottom: 2px solid #313b5e;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .destinations-list span {
            display: inline-block;
            padding: 4px 8px;
            margin: 2px 0;
            font-weight: 500;
            border-radius: 4px;
            color: #4a5568;
            font-size: 9pt;
            white-space: nowrap;
        }

        .destinations-list .text-blue-600 {
            color: #313b5e;
            margin: 0 5px;
        }

        /* Themes Grid (Simplified for Dompdf) */
        .themes-section {
            margin-top: 20px;
        }

        .theme-grid {
            /* Using floats for a simple grid layout for Dompdf */
            overflow: auto;
            /* To contain floated elements */
            margin-left: -5px;
            /* Adjust for padding/margin */
            margin-right: -5px;
        }

        .col-12 {
            width: 100%;
            clear: both;
        }

        .col-md-6 {
            width: 48%;
            /* Roughly half width */
            float: left;
            padding: 0 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .d-flex {
            display: block;
            /* Treat as block in Dompdf */
        }

        .align-items-center {
            vertical-align: middle;
        }

        .theme-icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
        }

        /* Clear floats after themes section */
        .theme-grid::after {
            content: "";
            display: table;
            clear: both;
        }

        /* --- Itinerary Section --- */
        .itinerary-card {

            border-radius: 8px;
            padding: 10px;
            margin: 20px 10px;

        }

        .day-header {
            display: block;
            /* Use block for vertical alignment in PDF */
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .day-number {
            font-size: 30pt;
            font-weight: bold;
            color: #313b5e;
            float: left;
            margin-right: 15px;
            line-height: 1;
        }

        .day-info {
            overflow: hidden;
            /* Contains the rest of the text */
        }

        .day-info .day-label {
            font-size: 10pt;
            color: #555;
            text-transform: uppercase;
        }

        .day-info h3 {
            font-size: 16pt;
            color: #313b5e;
            margin: 0;
        }

        .itinerary-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            margin-top: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        /* Program */
        .program-section h4 {
            color: #4a5568;
            font-size: 12pt;
            margin-bottom: 10px;
        }

        .program-box {
            border: 1px solid #e0e0e0;
            background-color: #f7f7f7;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .program-point {
            padding: 5px 0;
            border-bottom: 1px dashed #e0e0e0;
            font-size: 10pt;
        }

        .program-point:last-child {
            border-bottom: none;
        }

        /* Highlights */
        .highlights-section {
            margin: 20px 0;
        }

        .highlights-section .section-title {
            border-bottom: 2px solid #313b5e;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 12pt;
        }

        .highlights-grid {
            overflow: auto;
            margin-left: -5px;
            margin-right: -5px;
        }

        .highlight-item {
            width: 31%;
            /* Three items per row, slightly less for margin */
            float: left;
            padding: 0 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
            text-align: center;
        }

        .highlight-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .highlight-item p {
            font-size: 8pt;
            margin: 0;
            color: #555;
            font-weight: bold;
        }

        .highlights-grid::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Accommodation Box */
        .accommodation-box {
            overflow: auto;
            border-top: 2px solid #313b5e;
            padding-top: 15px;
            margin-top: 20px;
        }

        .accommodation-item {
            width: 33.33%;
            float: left;
            box-sizing: border-box;
            text-align: center;
        }

        .accommodation-icon {
            font-size: 10pt;
            color: #313b5e;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .accommodation-icon span {
            margin-left: 5px;
        }

        .accommodation-value {
            font-size: 10pt;
            color: #555;
        }

        .accommodation-box::after {
            content: "";
            display: table;
            clear: both;
        }

        /* --- Map Section --- */
        .map-page {
            text-align: center;
            margin: 20px 10px;
        }

        .map-page h3 {
            color: #313b5e;
            font-size: 18pt;
            margin-bottom: 15px;
        }

        .map-image {
            width: 95%;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* --- Vehicle Section --- */
        .vehicle-details-section {
            margin: 20px 10px;
        }

        .vehicle-details-section h2 {
            color: #313b5e;
            font-size: 18pt;
            margin-bottom: 15px;
            border-bottom: 2px solid #313b5e;
            padding-bottom: 5px;
        }

        .vehicle-card {
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            overflow: auto;
            /* Clear floats */
        }

        .vehicle-image-container {
            width: 45%;
            float: left;
            margin-right: 15px;
        }

        .vehicle-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .sub-images {
            overflow: auto;
            margin-bottom: 10px;
        }

        .sub-images img {
            width: 30%;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            float: left;
            margin-right: 5%;
        }

        .sub-images img:last-child {
            margin-right: 0;
        }

        .vehicle-info-list {
            overflow: hidden;
            /* Takes up remaining space */
        }

        .vehicle-info-list div {
            margin-bottom: 5px;
            font-size: 10pt;
            line-height: 1.3;
        }

        /* Clear floats after vehicle card */
        .vehicle-card::after {
            content: "";
            display: table;
            clear: both;
        }

        /* --- Inclusions/Exclusions/Policy Section --- */
        .info-list-section {
            margin: 20px 10px;
            padding: 8px;
        }

        .info-list-section h3 {
            color: #313b5e;
            font-size: 14pt;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .info-list {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 15px;
        }

        .info-list li {
            position: relative;
            margin-bottom: 5px;
            padding-left: 15px;
            line-height: 1.4;
            font-size: 10pt;
        }

        .info-list li::before {
            content: "•";
            color: #313b5e;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
            position: absolute;
            left: 0;
        }

        .info-list-section p {
            font-size: 10pt;
            line-height: 1.4;
        }

        .list-disc li::before {
            content: "—";
            color: #313b5e;
        }

        /* --- Footer --- */
        .footer-message {
            margin: 40px 10px 20px;
            padding: 15px;
            text-align: center;
            font-size: 9pt;
            line-height: 1.5;
            color: #555;
        }

        .footer-message strong {
            color: #313b5e;
        }

        .footer-message .thank-you {
            font-size: 14pt;
            font-weight: bold;
            color: #313b5e;
            margin-top: 15px;
        }

        .footer-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer-logo img {
            height: 200px;
            width: 250px;
        }
    </style>
</head>

<body>
    <div class="page-border"></div>
    <div class="card-body">
        <div class="row mb-3">


            <div class="content-wrapper">

                <div class="package-header" style="position: relative; width: 100%; padding: 20px 10px;">

                    <div style="position: absolute; top: 20px; left: 20px;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/vacayguider.png'))) }}"
                            style="height: 50px;">
                    </div>

                    <div style="position: absolute; top: 25px; right: 20px;" class="sri-lanka-badge">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/srilanka.png'))) }}"
                            alt="Sri Lanka Flag" style="height: 30px; display:block; margin-bottom: 5px;">

                    </div>

                    <h1>
                        {{ $package->heading }}
                    </h1>

                    @php
                        $cityList = [];
                        foreach ($tourSummaries as $summary) {
                            if ($summary->package_id == $package->id) {
                                $cities = explode(',', $summary->city);
                                foreach ($cities as $city) {
                                    $trimmed = trim($city);
                                    if (!empty($trimmed)) {
                                        $cityList[] = $trimmed;
                                    }
                                }
                            }
                        }
                        $cityList = array_values(array_unique($cityList));
                    @endphp

                    <div style="text-align: center; width: 100%; margin-top: 20px; margin-bottom: 10px;">

                        <div class="route-display"
                            style="display: inline-block; background-color: #f8f8f8; padding: 12px 25px; border-radius: 8px; ">

                            <div class="route-label"
                                style="font-weight: bold; color: #64748b; font-size: 10pt; margin-bottom: 5px; text-transform: uppercase;">
                                Route
                            </div>

                            <div class="route-path" style="font-size: 12pt; color: #313b5e;">
                                <span class="route-point">Airport</span>
                                <span class="route-arrow" style="color: #2563eb; margin: 0 5px;">→</span>

                                @foreach ($cityList as $index => $city)
                                    <span class="route-point" style="font-weight: 500;">{{ $city }}</span>
                                    @if ($index < count($cityList) - 1)
                                        <span class="route-arrow" style="color: #2563eb; margin: 0 5px;">→</span>
                                    @endif
                                @endforeach

                                @if (!empty($cityList))
                                    <span class="route-arrow" style="color: #2563eb; margin: 0 5px;">→</span>
                                @endif

                                <span class="route-point">Airport</span>
                            </div>

                        </div>
                    </div>
                </div>

                @php
                    $defaultImage = public_path('images/no-image.jpg');
                    $imagePath = $package->picture
                        ? public_path('storage/' . ltrim($package->picture, '/'))
                        : $defaultImage;

                    $imageData = base64_encode(file_get_contents($imagePath));
                    $mimeType = mime_content_type($imagePath);
                    $imageUrl = "data:$mimeType;base64,$imageData";
                @endphp

                <img src="{{ $imageUrl }}" alt="{{ $package->place ?? 'Tour Destination' }}" class="hero-image">

                @if (!empty($package->description))
                    <div class="description-section">
                        <p>{{ $package->description }}</p>
                    </div>
                @endif

                <div class="page-break"></div>

                <div class="pdf-full-page-wrapper">
                    <div class="pdf-middle-content">

                        <div class="tour-summary-card" id="summary-section">
                            <div class="d-flex align-items-center mb-4">
                                <h2>Tour Summary for {{ $package->days ?? 0 }} Days,
                                    {{ $package->nights ?? 0 }} Nights</h2>
                            </div>

                            <p class="summary-description">{{ $package->summary_description }}</p>

                            <div class="destinations-section">
                                <h3 class="section-title">Destinations</h3>
                                <div class="destinations-list">
                                    @php
                                        $cityList = [];
                                        foreach ($tourSummaries as $summary) {
                                            if ($summary->package_id == $package->id) {
                                                $cities = explode(',', $summary->city);
                                                foreach ($cities as $city) {
                                                    $trimmed = trim($city);
                                                    if (!empty($trimmed)) {
                                                        $cityList[] = $trimmed;
                                                    }
                                                }
                                            }
                                        }
                                        $cityList = array_values(array_unique($cityList));
                                    @endphp

                                    @foreach ($cityList as $index => $city)
                                        <span>{{ $city }}</span>
                                        @if ($index < count($cityList) - 1)
                                            <span style="color: #2563eb;">→</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="themes-section">
                                <h3 class="section-title">Themes</h3>
                                @php
                                    $themeList = [];
                                    foreach ($tourSummaries as $summary) {
                                        if ($summary->package_id == $package->id && !empty($summary->theme)) {
                                            $themes = explode(',', $summary->theme);
                                            foreach ($themes as $theme) {
                                                $trimmed = trim($theme);
                                                if (!empty($trimmed)) {
                                                    $themeList[] = $trimmed;
                                                }
                                            }
                                        }
                                    }
                                    $themeList = array_values(array_unique($themeList));
                                    $themeIcons = [
                                        'adventure' => 'https://d2xmwf00c85p5s.cloudfront.net/t5_0222b300ec.png',
                                        'beach' => 'https://d2xmwf00c85p5s.cloudfront.net/t2_a48d3a8dae.png',
                                        'city' => 'https://d2xmwf00c85p5s.cloudfront.net/2855998_b5bcbf5bea.png',
                                        'history' => 'https://d2xmwf00c85p5s.cloudfront.net/t6_005fa7fb20.png',
                                        'culture' => 'https://d2xmwf00c85p5s.cloudfront.net/t1_bfc05a4601.png',
                                    ];
                                @endphp

                                @if (!empty($themeList))
                                    <table style="width: 100%; border-collapse: collapse;">
                                        @foreach (array_chunk($themeList, 2) as $chunk)
                                            <tr>
                                                @foreach ($chunk as $theme)
                                                    @php
                                                        $iconUrl =
                                                            'https://d2xmwf00c85p5s.cloudfront.net/t5_0222b300ec.png';
                                                        foreach ($themeIcons as $key => $url) {
                                                            if (str_contains(strtolower($theme), $key)) {
                                                                $iconUrl = $url;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <td style="width: 50%; padding: 10px 0;">
                                                        <img src="{{ $iconUrl }}"
                                                            style="height: 20px; vertical-align: middle; margin-right: 8px;">
                                                        <span
                                                            style="vertical-align: middle;">{{ $theme }}</span>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div style="page-break-before: always;"></div>
                @foreach ($package->detailItineraries as $index => $itinerary)
                    <div class="itinerary-card" style="page-break-after: always;">

                        <table class="layout-table">
                            <tr>
                                <td class="day-number-cell">
                                    {{ str_pad($itinerary->day ?? $index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="padding-left: 15px;">
                                    <div style="font-size: 10pt; color: #555; text-transform: uppercase;">
                                        Day {{ $itinerary->day ?? $index + 1 }}
                                    </div>
                                    <h3 style="font-size: 16pt; color: #313b5e; margin: 0;">
                                        {{ $itinerary->title ?? 'Day Activity' }}
                                    </h3>
                                </td>
                            </tr>
                        </table>

                        <p class="summary-description">{{ $itinerary->description ?? '-' }}</p>

                        @php
                            $defaultImage = public_path('images/no-image.jpg');
                            $coverPath = $itinerary->pictures
                                ? public_path('storage/' . ltrim($itinerary->pictures, '/'))
                                : $defaultImage;

                            // Simple logic for image
                            if (!file_exists($coverPath)) {
                                $coverPath = $defaultImage;
                            }
                            $coverData = base64_encode(file_get_contents($coverPath));
                            $coverMime = mime_content_type($coverPath);
                        @endphp

                        <img src="data:{{ $coverMime }};base64,{{ $coverData }}" class="itinerary-image">

                        <div class="program-section">
                            <h4 style="margin-bottom: 10px; color: #4a5568;">Day {{ $itinerary->day ?? $index + 1 }}
                                Program</h4>
                            <div class="program-box" style="padding: 10px; border-radius: 4px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    @foreach (collect($itinerary->program_points)->take(4) as $point)
                                        <tr>
                                            <td
                                                style="vertical-align: top; width: 15px; padding: 5px 0; color: #313b5e; font-weight: bold;">
                                                •</td>
                                            <td style="padding: 5px 0; font-size: 10pt; line-height: 1.4;">
                                                {{ trim($point) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <table class="accommodation-table">
                            <tr>
                                <td>
                                    <div style="font-weight: bold; color: #313b5e; font-size: 9pt;">Accommodation</div>
                                    <div style="color: #555; font-size: 9pt;">
                                        {{ $itinerary->overnight_stay ?? 'Not specified' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: bold; color: #313b5e; font-size: 9pt;">Meal Plan</div>
                                    <div style="color: #555; font-size: 9pt;">{{ $itinerary->meal_plan ?? 'None' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: bold; color: #313b5e; font-size: 9pt;">Travel Time</div>
                                    <div style="color: #555; font-size: 9pt;">
                                        {{ $itinerary->approximate_travel_time ?? 'N/A' }}</div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    @if ($package->hilight_show_hide == 1 && $itinerary->highlights->isNotEmpty())
                        <div class="highlights-section" style="padding: 20px;">
                            <h3 class="section-title"
                                style="margin-bottom: 15px; font-family: sans-serif; text-align: center;">
                                {{ $itinerary->place_name ?? 'Destination' }} Highlights
                            </h3>
                            <table
                                style="width: 100%; border-collapse: separate; border-spacing: 15px; table-layout: fixed;">
                                {{-- Chunking into 2 items per row for a 2-column layout --}}
                                @foreach ($itinerary->highlights->take(6)->chunk(2) as $row)
                                    <tr>
                                        @foreach ($row as $highlight)
                                            @php
                                                $base64Image = null;
                                                $imagePath = null;

                                                if (is_array($highlight->images) && !empty($highlight->images)) {
                                                    $imagePath = $highlight->images[0];
                                                } elseif (is_string($highlight->images)) {
                                                    $imagePath = $highlight->images;
                                                }

                                                $fullPath = $imagePath
                                                    ? storage_path('app/public/' . ltrim($imagePath, '/'))
                                                    : null;

                                                try {
                                                    if ($fullPath && file_exists($fullPath) && is_file($fullPath)) {
                                                        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                                                        $imageData = base64_encode(file_get_contents($fullPath));
                                                        $base64Image =
                                                            'data:image/' . $extension . ';base64,' . $imageData;
                                                    } else {
                                                        $placeholder = public_path('images/no-image.jpg');
                                                        if (file_exists($placeholder)) {
                                                            $imageData = base64_encode(file_get_contents($placeholder));
                                                            $base64Image = 'data:image/jpeg;base64,' . $imageData;
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                    $base64Image = null;
                                                }
                                            @endphp

                                            {{-- Set width to 50% for 2 columns --}}
                                            <td style="width: 50%; text-align: center; vertical-align: top;">
                                                {{-- Increased height from 110px to 180px for a "bigger" look --}}
                                                <div
                                                    style="width: 100%; height: 180px; margin-bottom: 8px; overflow: hidden;">
                                                    @if ($base64Image)
                                                        <img src="{{ $base64Image }}"
                                                            style="width: 100%; height: 180px; object-fit: cover; border-radius: 6px;">
                                                    @else
                                                        <div
                                                            style="width: 100%; height: 180px; background: #f0f0f0; border-radius: 6px; border: 1px solid #ddd;">
                                                            <span
                                                                style="font-size: 8pt; color: #999; line-height: 180px;">No
                                                                Image</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p
                                                    style="font-size: 10pt; font-weight: bold; color: #333; margin: 0; padding-top: 5px;">
                                                    {{ $highlight->highlight_places }}
                                                </p>
                                            </td>
                                        @endforeach

                                        {{-- Fill remaining <td> if row has only 1 item --}}
                                        @if (count($row) < 2)
                                            <td style="width: 50%;"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                @endforeach

                @if (!empty($package->map_image))
                    <div style="page-break-before: always;"></div>

                    @php
                        $defaultMapImage = public_path('assets/img/default-map.jpg');
                        $mapPath = $package->map_image
                            ? public_path('storage/' . ltrim($package->map_image, '/'))
                            : $defaultMapImage;

                        // Ensure we don't crash if the default map is also missing
$mapData = '';
                        if (file_exists($mapPath)) {
                            $mapData = base64_encode(file_get_contents($mapPath));
                            $mapMime = mime_content_type($mapPath);
                        } elseif (file_exists($defaultMapImage)) {
                            $mapData = base64_encode(file_get_contents($defaultMapImage));
                            $mapMime = mime_content_type($defaultMapImage);
                        }

                        $mapImage = $mapData ? "data:$mapMime;base64,$mapData" : null;
                    @endphp

                    @if ($mapImage)
                        {{-- Use 'page-break-inside: avoid' to keep heading and image together --}}
                        <div class="map-page" style="page-break-inside: avoid; width: 100%; text-align: center;">
                            <h3 style="margin-top: 0; margin-bottom: 15px;">Tour Map</h3>

                            <div style="width: 100%; text-align: center;">
                                <img src="{{ $mapImage }}" alt="Tour Route Map"
                                    style="width: 100%; max-width: 700px; max-height: 800px; object-fit: contain; display: block; margin: 0 auto;">
                            </div>
                        </div>
                    @endif
                @endif


                @if ($package->packageVehicles && $package->packageVehicles->isNotEmpty())
                    <div style="page-break-before: always;"></div>
                    <div class="vehicle-details-section">
                        <h2 style="text-align: center; color: #313b5e; margin-bottom: 20px;">Vehicle Details</h2>

                        @foreach ($package->packageVehicles as $vehicle)
                            @php
                                $defaultVehicleImage = public_path('images/no-vehicle.jpg');
                                $vehicleImagePath = $vehicle->vehicle_image
                                    ? public_path('storage/' . ltrim($vehicle->vehicle_image, '/'))
                                    : $defaultVehicleImage;

                                $vehicleImageUrl = '';
                                if (file_exists($vehicleImagePath)) {
                                    $vehicleImageData = base64_encode(file_get_contents($vehicleImagePath));
                                    $vehicleImageMime = mime_content_type($vehicleImagePath);
                                    $vehicleImageUrl = "data:$vehicleImageMime;base64,$vehicleImageData";
                                }

                                $subImages = [];
                                if (!empty($vehicle->sub_image)) {
                                    $subImagesArray = is_array($vehicle->sub_image)
                                        ? $vehicle->sub_image
                                        : json_decode($vehicle->sub_image, true);

                                    foreach ((array) $subImagesArray as $subImg) {
                                        $subImgPath = public_path('storage/' . ltrim($subImg, '/'));
                                        if (file_exists($subImgPath)) {
                                            $subImgData = base64_encode(file_get_contents($subImgPath));
                                            $subImgMime = mime_content_type($subImgPath);
                                            $subImages[] = "data:$subImgMime;base64,$subImgData";
                                        }
                                    }
                                }
                            @endphp

                            <table
                                style="width: 100%; border-radius: 8px; margin-bottom: 30px; padding: 20px; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center; padding-bottom: 5px;">
                                        <img src="{{ $vehicleImageUrl }}"
                                            style="width: 100%; height: 400px; object-fit: cover; border-radius: 8px; display: block; margin: 0 auto;">
                                    </td>
                                </tr>

                                @if (!empty($subImages))
                                    <tr>
                                        <td style="padding-bottom: 20px;">
                                            <table style="width: 100%; border-collapse: collapse; text-align: center;">
                                                <tr>
                                                    @foreach ($subImages as $subImageUrl)
                                                        <td style="padding: 0 5px;">
                                                            <img src="{{ $subImageUrl }}"
                                                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 4px;">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="padding-top: 20px;">
                                        <div
                                            style="font-size: 16pt; color: #313b5e; font-weight: bold; text-align: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 8px; margin-bottom: 15px;">
                                            {{ $vehicle->name ?? 'Vehicle Specifications' }}
                                        </div>

                                        <table
                                            style="width: 100%; border-collapse: collapse; background-color: #f9fbfd; border-radius: 8px;">
                                            <tr>
                                                <td
                                                    style="width: 50%; padding: 15px; vertical-align: top; border-right: 1px solid #eef2f6;">
                                                    <table style="width: 100%; font-size: 10.5pt; line-height: 2;">
                                                        <tr>
                                                            <td style="color: #64748b; width: 40%;">Make</td>
                                                            <td style="color: #1e293b; font-weight: bold;">:
                                                                {{ $vehicle->make ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="color: #64748b;">Model</td>
                                                            <td style="color: #1e293b; font-weight: bold;">:
                                                                {{ $vehicle->model ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="color: #64748b;">Condition</td>
                                                            <td style="color: #1e293b; font-weight: bold;">:
                                                                {{ ucfirst($vehicle->condition ?? 'N/A') }}</td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td style="width: 50%; padding: 15px; vertical-align: top;">
                                                    <table style="width: 100%; font-size: 10.5pt; line-height: 2;">
                                                        <tr>
                                                            <td style="color: #64748b; width: 50%;">Seats Available
                                                            </td>
                                                            <td style="color: #1e293b; font-weight: bold;">:
                                                                {{ $vehicle->seats ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="color: #64748b;">Max Capacity</td>
                                                            <td style="color: #1e293b; font-weight: bold;">:
                                                                {{ $vehicle->max_seating_capacity ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="color: #64748b;">Air Conditioned</td>
                                                            <td style="color: #1e293b; font-weight: bold;">: Yes</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                @endif


                @if ($packageInclusions->isNotEmpty())

                    <div class="info-list-section ">
                        @foreach ($packageInclusions as $inclusion)
                            <div style="page-break-before: always;"></div>
                            @php
                                // Decode points safely
                                $points = $inclusion->points;

                                if (is_string($points)) {
                                    $decoded = json_decode($points, true);
                                    $points = is_array($decoded) ? $decoded : [$points]; // Wrap single string in array
                                }

                                // Ensure points is always an array
                                $points = $points ?? [];
                            @endphp

                            <h3>{{ ucfirst($inclusion->heading) }}</h3>
                            <ul class="info-list">
                                @foreach ($points as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>

                            @if (!empty($inclusion->note))
                                <p style="margin-bottom: 10px;">
                                    <strong style="color: #000;">Note :</strong> {!! $inclusion->note !!}
                                </p>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div style="page-break-before: always;"></div>
                    {{-- Fallback Hardcoded Content --}}
                    <div class="info-list-section ">
                        <h3>Tour Inclusions</h3>
                        <ul class="info-list">
                            <li>Airport pick up and drop off</li>
                            <li>Assistance at the Airport</li>
                            <li>Accommodation with breakfast and dinner basis on mentioned hotels below</li>
                            <li>Private luxury car (air-conditioned)</li>
                            <li>Private English-Speaking driver for the entire journey</li>
                            <li>Fuel & local insurance for the vehicle</li>
                            <p style="margin-bottom: 10px;">
                                <strong style="color: #000;">Note :</strong> Please note that all journey durations are
                                estimates and may vary due to traffic, road conditions, and weather. These times are
                                calculated for direct travel without stops and are provided as a guideline only.
                            </p>
                        </ul>
                    </div>

                    <div class="info-list-section">
                        <h3>Tour Exclusions</h3>
                        <ul class="info-list">
                            <li>Air tickets NOT included</li>
                            <li>Sightseeing entrance charges</li>
                            <li>Meals not mentioned in the itinerary</li>
                            <li>Camera & video permits</li>
                            <li>Insurances</li>
                            <li>Guide/Driver tips</li>
                            <li>Personal expenses and shopping expense</li>
                            <li>Late check-outs & early check-in charges</li>
                            <li>Visa cost</li>
                            <p style="margin-bottom: 10px;">
                                <strong style="color: #000;">Note :</strong> Please note that if the information
                                mentioned above varies according to the customer's preference, the corresponding charges
                                will also be included.
                            </p>
                        </ul>
                    </div>

                    <div class="info-list-section">
                        <h3>Cancellation Policy</h3>
                        <p class="mb-3">
                            <strong>In case of cancellation:</strong> The following cancellation charges will be
                            applicable.
                        </p>
                        <ul class="info-list list-disc">
                            <li>No Show: Zero refund.</li>
                            <li>Cancellations made prior to 30 days from the scheduled start of a tour: 80% of total
                                tour fee will be refunded.</li>
                        </ul>
                    </div>
                @endif

                <div style="page-break-before: always;"></div>
                <div class="footer-message">
                    <p>
                        At <strong>VacayGuider</strong>, the journey doesn't end when the trip does — it lingers
                        in the stories shared, the photos reminisced, and the plans for the next adventure.
                        But above all, we understand the sanctity of trust. Every service we offer, every tour we
                        curate,
                        prioritizes the safety and well-being of our travelers.
                        With <strong>VacayGuider</strong>, you're not just booking a trip — you're ensuring an
                        experience
                        that's safe, seamless, and truly unforgettable.
                        May your trip be full of adventure, joy, and amazing experiences. Stay with us.
                    </p>
                    <p class="thank-you">Thank You</p>
                </div>


                <div class="footer-logo">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/vacayguider.png'))) }}"
                        alt="VacayGuider Logo">
                </div>

            </div>


        </div>
    </div>
</body>

</html>
