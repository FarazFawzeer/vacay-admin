@extends('layouts.vertical', ['subtitle' => 'View Tour Package'])




<style>
    /* PDF Page Settings */
    @page {
        size: A4;
        margin: 20mm;
    }

    @media print {
        body {
            margin: 0;
            padding: 20px;
        }

        .no-print {
            display: none !important;
        }

        .page-break {
            page-break-before: always;
        }
    }


    .theme-grid {
        font-size: 1rem;
        /* same as text-base */
        color: #4b5563;
        /* same as Tailwind's text-gray-600 */
    }

    .theme-grid .theme-icon {
        width: 22px;
        height: 22px;
        object-fit: contain;
    }

    .theme-grid .d-flex {
        gap: 8px;
        /* mimic Tailwind's gap-2 */
    }

    .package-header {

        color: white;

        border-radius: 20px;
        margin-bottom: 40px;
        position: relative;

    }


    @keyframes float {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        50% {
            transform: translate(-20px, -20px) scale(1.05);
        }
    }

    .header-content {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Country Badge */
    .sri-lanka-badge {
        background: linear-gradient(135deg, #96c93e 0%, #7db32d 100%);
        color: white;
        padding: 10px 30px;
        border-radius: 50px;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 25px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        animation: slideDown 0.6s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Package Title */
    .package-title {
        font-size: 2rem;
        font-weight: 800;
        margin: 0 0 25px 0;
        line-height: 1.2;
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.8s ease 0.2s both;
        background: linear-gradient(to right, #ffffff, #f0f9ff);

        background-clip: text;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Duration Info */
    .duration-info {
        margin-bottom: 35px;
        animation: fadeIn 0.8s ease 0.4s both;
    }

    .duration-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;

        color: var(--taplox-heading-color);
        transition: all 0.3s ease;
    }

    .duration-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        background: rgba(255, 255, 255, 0.2);
    }

    .duration-badge i {
        font-size: 1.3rem;
        color: #96c93e;
    }

    /* Route Display */
    .route-display {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        animation: fadeIn 0.8s ease 0.6s both;
    }

    .route-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #96c93e;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .route-label::before,
    .route-label::after {
        content: '';
        width: 40px;
        height: 2px;
        background: linear-gradient(to right, transparent, #96c93e);
    }

    .route-label::after {
        background: linear-gradient(to left, transparent, #96c93e);
    }

    .route-path {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px 15px;
    }

    .route-point {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 1rem;
        color: #1a202c;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }



    .route-point i {
        font-size: 0.9rem;
        color: #8c8c8c;
    }

    .route-arrow {
        color: #8c8c8c;
        font-weight: bold;
        font-size: 1.3rem;
        opacity: 0.9;
        text-shadow: 0 2px 8px rgba(150, 201, 62, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .package-header {
            padding: 35px 25px;
            border-radius: 16px;
        }

        .package-title {
            font-size: 1.8rem;
        }

        .duration-badge {
            padding: 12px 25px;
            font-size: 0.95rem;
        }

        .route-display {
            padding: 20px 15px;
        }

        .route-point {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .route-arrow {
            font-size: 1.1rem;
        }

        .sri-lanka-badge {
            font-size: 0.85rem;
            padding: 8px 20px;
        }
    }

    @media (max-width: 480px) {
        .package-header {
            padding: 30px 20px;
        }

        .package-title {
            font-size: 1.5rem;
        }

        .route-path {
            gap: 5px 10px;
        }

        .route-point {
            font-size: 0.8rem;
            padding: 5px 10px;
        }

        .route-point i {
            font-size: 0.75rem;
        }
    }

    /* Hero Image */
    .hero-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 16px;
        margin: 30px 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }


    /* Description Section */
    .description-section {
        padding: 10px;
        border-radius: 12px;
        margin: 30px 0;

    }

    .description-section p {
        text-align: justify;
        color: #4a5568;
        font-size: 1rem;
        line-height: 1.8;
    }

    /* Tour Summary Card */
    .tour-summary-card {
        background: linear-gradient(135deg, #eff5ff 0%, #e0f2fe 100%);
        border-radius: 16px;
        padding: 40px;
        margin: 40px 0;

    }

    .tour-summary-card h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #0d4e6b;
        margin-bottom: 20px;
    }


    .summary-description {
        color: #4a5568;
        font-size: 1.1rem;
        line-height: 1.8;
        margin: 25px 0;
        text-align: justify;
    }

    /* Destinations Section */
    .destinations-section,
    .themes-section {
        margin: 30px 0;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 15px;
    }

    .destinations-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .destinations-list span {
        font-size: 1rem;
        color: #4a5568;
        font-weight: 500;
    }

    /* Themes Grid */
    .themes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .theme-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .theme-item img {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    /* Itinerary Cards */
    .itinerary-card {

        border-radius: 16px;
        padding: 40px;
        margin: 30px 0;

    }

    .day-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .day-number {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #96c93e 0%, #7db32d 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.3rem;
        margin-right: 20px;
        box-shadow: 0 4px 10px rgba(150, 201, 62, 0.3);
    }

    .day-info h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0;
    }

    .day-label {
        font-size: 0.85rem;
        color: #709929;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    /* Itinerary Image */
    .itinerary-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px;
        margin: 25px 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    /* Program Points */
    .program-section {
        margin: 30px 0;
    }

    .program-section h4 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 20px;
    }

    .program-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 25px;
    }

    .program-point {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .program-point::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #1a202c;
        border-radius: 50%;
        margin-right: 15px;
        margin-top: 8px;
        flex-shrink: 0;
    }

    .program-point:last-child {
        margin-bottom: 0;
    }

    /* Highlights Section */
    .highlights-section {
        margin: 40px 0;
    }

    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .highlight-item {
        text-align: center;
    }

    .highlight-item img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .highlight-item img:hover {
        transform: scale(1.05);
    }

    .highlight-item p {
        margin-top: 12px;
        font-size: 0.95rem;
        color: #4a5568;
        font-weight: 500;
    }

    /* Accommodation Box */
    .accommodation-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 25px;
        margin: 30px 0;
    }

    .accommodation-item {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .accommodation-item:last-child {
        border-bottom: none;
    }

    .accommodation-icon {
        width: 180px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-shrink: 0;
    }

    .accommodation-icon i {
        font-size: 1.3rem;
        color: #64748b;
        width: 24px;
    }

    .accommodation-icon span {
        font-weight: 600;
        color: #2d3748;
    }

    .accommodation-value {
        flex: 1;
        color: #1a202c;
        font-weight: 500;
    }

    /* Map Section */
    .map-section {
        margin: 50px 0;
    }

    .map-section h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 20px;
    }

    .map-image {
        max-width: 500px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    /* Inclusions/Exclusions Lists */
    .info-list-section {
        background: white;
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 10px;
        border-radius: 12px;
        margin: 30px 0;

    }

    .info-list-section h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0d4e6b;
        margin-bottom: 25px;
    }

    .info-list {
        list-style: none;
        padding: 0;
    }

    .info-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        color: #4a5568;
    }

    .info-list li::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #727373;
        border-radius: 50%;
        margin-right: 15px;
        margin-top: 8px;
        flex-shrink: 0;
    }

    .note-text {
        margin-top: 20px;
        padding: 15px;
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        color: #78350f;
    }

    .note-text strong {
        color: #92400e;
    }

    /* Footer Message */
    .footer-message {
        max-width: 800px;
        margin: 60px auto;
        padding: 40px;
        text-align: center;
        border-top: 3px solid #0d4e6b;
    }

    .footer-message p {
        font-size: 1rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 20px;
    }

    .footer-message .thank-you {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0d4e6b;
        margin-top: 30px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .package-header {
            padding: 30px 20px;
        }

        .package-header h1 {
            font-size: 1.8rem;
        }

        .hero-image {
            height: 300px;
        }

        .tour-summary-card {
            padding: 25px;
        }

        .itinerary-card {
            padding: 25px;
        }

        .day-number {
            width: 50px;
            height: 50px;
            font-size: 1.1rem;
        }

        .accommodation-icon {
            width: 140px;
            gap: 10px;
        }

        .highlights-grid {
            grid-template-columns: 1fr;
        }

        .map-image {
            width: 100%;
            max-width: 100%;
        }
    }

    /* Utility Classes */
    .mb-30 {
        margin-bottom: 30px;
    }

    .mt-30 {
        margin-top: 30px;
    }

    .text-center {
        text-align: center;
    }

    .font-bold {
        font-weight: 700;
    }
</style>
@section('content')
    @include('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'View Details'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="position: relative; z-index: 10;">
            <h5 class="card-title mb-0">{{ $package->heading }}</h5>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary btn-sm">
                    Back to List
                </a>
                <a href="{{ route('admin.package.pdf', $package->id) }}" class="btn btn-primary btn-sm" target="_blank">
                    Generate PDF
                </a>
            </div>
        </div>


        <div class="card-body">
            <div class="row mb-3">
                <div class="content-wrapper">

                    <!-- Package Header -->
                    <div class="package-header">
                        <div class="header-content">
                            <!-- Country Badge -->
                            <div class="sri-lanka-badge">{{ $package->country_name ?? 'Sri Lanka' }}</div>

                            <!-- Main Heading - Centered -->
                            <h1 class="package-title" style="color: #313b5e;">{{ $package->heading }}</h1>

                            <!-- Duration Info -->
                            {{-- <div class="duration-info">
                                <div class="duration-badge">

                                    <span>{{ $package->days ?? 0 }} Days & {{ $package->nights ?? 0 }} Nights</span>
                                </div>
                            </div> --}}

                            <!-- Route Display -->
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

                            <div class="route-display">
                                <div class="route-label">Tour Route</div>
                                <div class="route-path">
                                    <span class="route-point">
                                        <img src="{{ asset('images/plane-departure.svg') }}" alt="Plane Departure"
                                            width="16" height="16">
                                        Airport
                                    </span>

                                    <span class="route-arrow">→</span>

                                    @foreach ($cityList as $index => $city)
                                        <span class="route-point">

                                            {{ $city }}
                                        </span>
                                        <span class="route-arrow">→</span>
                                    @endforeach

                                    <span class="route-point">

                                        Airport
                                        <img src="{{ asset('images/plane-departure.svg') }}" alt="Plane Departure"
                                            width="16" height="16">
                                    </span>

                                </div>
                            </div>
                        </div>
                    </div>


                    @php
                        $defaultImage = asset('images/no-image.jpg');
                        $imageUrl = $package->picture
                            ? asset('storage/' . ltrim($package->picture, '/'))
                            : $defaultImage;
                    @endphp

                    <img src="{{ $imageUrl }}" alt="{{ $package->place }}" class="hero-image" loading="lazy">





                    <!-- Description Section -->
                    @if (!empty($package->description))
                        <div class="description-section">
                            <p>{{ $package->description }}</p>
                        </div>
                    @endif


                    <div class="tour-summary-card" id="summary-section">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                            <h2>Tour Summary</h2>
                            <div class="duration-badge">

                                <span>{{ $package->days ?? 0 }} Days, {{ $package->nights ?? 0 }} Nights</span>
                            </div>
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
                                        <span class="text-blue-600">→</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>


                        <!-- Themes -->


                        <div class="themes-section">
                            <h3 class="section-title">Themes</h3>
                            <div class="themes-grid">
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
                                @endphp

                                @if (!empty($themeList))
                                    <div class="row ms-2 text-secondary theme-grid">
                                        @foreach ($themeList as $theme)
                                            @php
                                                // Define theme icons with keywords and their URLs
                                                $themeIcons = [
                                                    'adventure' =>
                                                        'https://d2xmwf00c85p5s.cloudfront.net/t5_0222b300ec.png',
                                                    'beach' =>
                                                        'https://d2xmwf00c85p5s.cloudfront.net/t2_a48d3a8dae.png',
                                                    'city' =>
                                                        'https://d2xmwf00c85p5s.cloudfront.net/2855998_b5bcbf5bea.png',
                                                    'history' =>
                                                        'https://d2xmwf00c85p5s.cloudfront.net/t6_005fa7fb20.png',
                                                    'culture' =>
                                                        'https://d2xmwf00c85p5s.cloudfront.net/t1_bfc05a4601.png',
                                                ];

                                                // Default icon
                                                $iconUrl = 'https://d2xmwf00c85p5s.cloudfront.net/t5_0222b300ec.png';

                                                // Match theme to icon
                                                foreach ($themeIcons as $key => $url) {
                                                    if (str_contains(strtolower($theme), $key)) {
                                                        $iconUrl = $url;
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ $iconUrl }}" alt="{{ $theme }} icon"
                                                        class="theme-icon me-2">
                                                    <span>{{ $theme }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>


                    <!-- Itineraries -->
                    @foreach ($package->detailItineraries as $itinerary)
                        <div class="itinerary-card page-break">
                            <div class="day-header">
                                <div class="day-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="day-info">
                                    <div class="day-label">Day {{ $index + 1 }}</div>
                                    <h3>{{ $itinerary->title ?? 'Day Activity' }}</h3>
                                </div>
                            </div>

                            <p class="summary-description">{{ $itinerary->description ?? '-' }}</p>

                            @php
                                $defaultImage = asset('images/no-image.jpg');
                                $coverImage = $itinerary->pictures
                                    ? asset('storage/' . ltrim($itinerary->pictures, '/'))
                                    : $defaultImage;
                            @endphp

                            <img src="{{ $coverImage }}" alt="{{ $itinerary->place_name }}" class="itinerary-image"
                                loading="lazy">



                            <!-- Program -->

                            @php $programs = explode('|', $itinerary->program); @endphp
                            <div class="program-section">
                                <h4>Day {{ $index + 1 }} Program</h4>
                                <div class="program-box">
                                    @foreach (collect($itinerary->program_points)->take(4) as $point)
                                        <div class="program-point">{{ trim($point) }}</div>
                                    @endforeach
                                </div>
                            </div>


                            <!-- Highlights -->
                            @if ($itinerary->highlights->isNotEmpty())
                                <div class="highlights-section">
                                    <h3 class="section-title">{{ $itinerary->place_name }} Highlights</h3>
                                    <div class="highlights-grid">
                                        @foreach ($itinerary->highlights->take(6) as $highlight)
                                            @php
                                                $defaultImage = asset('images/no-image.jpg');
                                                $images = [];

                                                if (!empty($highlight->images)) {
                                                    if (is_array($highlight->images)) {
                                                        $images = $highlight->images;
                                                    } else {
                                                        $decoded = @json_decode($highlight->images, true);
                                                        if (is_array($decoded) && count($decoded) > 0) {
                                                            $images = $decoded;
                                                        } else {
                                                            $images = [$highlight->images];
                                                        }
                                                    }
                                                }

                                                $imageUrls = collect($images)
                                                    ->map(function ($img) use ($defaultImage) {
                                                        return $img
                                                            ? asset('storage/' . ltrim($img, '/'))
                                                            : $defaultImage;
                                                    })
                                                    ->all();
                                            @endphp

                                            <div class="highlight-item">
                                                <img src="{{ $imageUrls[0] ?? $defaultImage }}"
                                                    alt="{{ $highlight->highlight_places ?? 'Highlight' }}" loading="lazy">
                                                <p>{{ $highlight->highlight_places ?? 'Highlight' }}</p>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endif

                            <!-- Accommodation -->
                            <div class="accommodation-box">
                                <div class="accommodation-item">
                                    <div class="accommodation-icon">
                                        <i class="fas fa-hotel"></i><span>Accommodation</span>
                                    </div>
                                    <div class="accommodation-value">{{ $itinerary->overnight_stay ?? 'Not specified' }}
                                    </div>
                                </div>
                                <div class="accommodation-item">
                                    <div class="accommodation-icon">
                                        <i class="fas fa-utensils"></i><span>Meal Plan</span>
                                    </div>
                                    <div class="accommodation-value">{{ $itinerary->meal_plan ?? 'None' }}</div>
                                </div>
                                <div class="accommodation-item">
                                    <div class="accommodation-icon">
                                        <i class="far fa-clock"></i><span>Travel Time</span>
                                    </div>
                                    <div class="accommodation-value">{{ $itinerary->approximate_travel_time ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <!-- Map Section -->
                    @if (!empty($package->map_image))
                        @php
                            $defaultMapImage = asset('assets/img/default-map.jpg');
                            $mapImage = $package->map_image
                                ? asset('storage/' . ltrim($package->map_image, '/'))
                                : $defaultMapImage;
                        @endphp

                        <div class="map-section">
                            <h3>Tour Map</h3>
                            <img src="{{ $mapImage }}" alt="Tour Route Map" class="map-image" loading="lazy">
                        </div>
                    @endif


                    <!-- Inclusions -->

                    <div class="info-list-section page-break">
                        <h3>Tour Inclusions</h3>
                        <ul class="info-list">
                            <li>Airport pick up and drop off</li>
                            <li>Assistance at the Airport</li>
                            <li>Accommodation with breakfast and dinner basis on mentioned hotels
                                below</li>
                            <li>Private luxury car (air-conditioned)</li>
                            <li>Private English-Speaking driver for the entire journey</li>
                            <li>Fuel & local insurance for the vehicle</li>
                            <p style="margin-bottom: 10px;">
                                <strong style="color: #000;">Note :</strong> Please note that all journey
                                durations are estimates and may vary due to traffic, road conditions, and
                                weather. These times are calculated for direct travel without stops and are
                                provided as a guideline only.
                            </p>

                        </ul>
                    </div>


                    <div class="info-list-section">
                        <h3>Tour Exclusions</h3>
                        <ul class="info-list">

                            <li>Air tickets NOT included</li>
                            <li>Sightseeing entrance charges</li>
                            <li>Meals not mentioned in the itinerary< /li>
                            <li>Camera & video permits</li>
                            <li>Insurances</li>
                            <li>Guide/Driver tips</li>
                            <li>Personal expenses and shopping expense</li>
                            <li>Late check-outs & early check-in charges</li>
                            <li>Visa cost</li>
                            <p style="margin-bottom: 10px;">
                                <strong style="color: #000;">Note :</strong> Please note that if the
                                information mentioned
                                above varies according to the customer's preference, the corresponding
                                charges will also be included.
                            </p>
                        </ul>
                    </div>



                    <div class="info-list-section">
                        <h3>Cancellation Policy</h3>
                        <p class="mb-3">
                            <strong>In case of cancellation:</strong> The following cancellation charges will be
                            applicable.
                        </p>

                        <ul class="list-disc pl-6 space-y-2">
                            <li>No Show: Zero refund.</li>
                            <li>Cancellations made prior to 30 days from the scheduled start of a tour: 80% of
                                total tour fee will be refunded.</li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="footer-message">
                        <p>
                            At <strong>VacayGuider</strong>, the journey doesn't end when the trip does, it lingers
                            in the stories shared,
                            the photos reminisced, and the plans for the next adventure. But above all, we
                            understand
                            the sanctity of trust. Every service we offer, every tour we curate, prioritizes the
                            safety and
                            well-being of our travelers. With <strong>VacayGuider</strong>, you're not just booking
                            a trip, you're ensuring an experience that's safe, seamless,
                            and truly unforgettable. May your trip be full of adventure, joy, and amazing
                            experiences. Stay with us
                        </p>
                        <p class="thank-you">Thank You</p>
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection
