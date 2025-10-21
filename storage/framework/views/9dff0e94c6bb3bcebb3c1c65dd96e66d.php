<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo e($package->heading ?? 'Tour Package PDF'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        .footer-logo {
            text-align: center;
        }

        .footer-logo img {
            width: 150px;
            /* Adjust size as needed */
            height: auto;
            display: inline-block;
        }

        @media (max-width: 992px) {
            .themes-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .themes-grid {
                grid-template-columns: 1fr;
            }
        }

        /* PDF Page Settings */
        @page {
            size: A4;
        }

        body::before {
            content: "";
            position: fixed;
            top: 2mm;
            left: 2mm;
            right: 2mm;
            bottom: 2mm;
            border: 2px solid #0d4e6b;
            /* light gray border */
            z-index: -1;
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
            position: relative;
            /* reduce this as needed */
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
            /* Reduce top margin if needed */
            margin-top: 0;
        }

        /* Country Badge */
        .sri-lanka-badge {

            color: #7db32d;
            border-radius: 50px;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: 2px;

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
            color: #313b5e;
            /* Use solid color */
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease 0.2s both;
            background: none;
            /* Remove gradient */
            background-clip: unset;
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
            white-space: nowrap;
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
        /* Route Display */
        .route-display {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);

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
            background: #96c93e;
        }

        .route-label::after {
            background: #96c93e;
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
            height: 400px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 30px 0;
        }



        /* Description Section */
        .description-section {
            padding: 10px;
            border-radius: 12px;


        }

        .description-section p {
            text-align: justify;
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.8;
        }

        .pdf-center-wrapper {
            display: flex;
            justify-content: center;
            /* horizontal center */
            align-items: center;
            /* vertical center */
            min-height: 100vh;
            /* full page height */
            padding: 40px;
            /* add some space from edges */
            box-sizing: border-box;
        }


        /* Tour Summary Card */
        .tour-summary-card {
            background: linear-gradient(135deg, #eff5ff 0%, #e0f2fe 100%);
            border-radius: 16px;
            padding: 40px;
            margin: 40px 0;
        }

        .tour-summary-card h2 {
            font-size: 1.7rem;
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
            grid-template-columns: repeat(3, 1fr);
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

        .day-info {
            margin-bottom: 0;
            /* optional: remove extra space after the block */
            padding: 0;
            /* optional: remove padding */
        }

        .day-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0;
            line-height: 1.2;
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
            text-align: start;
            padding-top: 30px;
        }

        .highlights-grid {
            padding-top: 25px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            justify-items: center;
            align-items: start;
            padding-bottom: 50px;
        }

        /* Individual highlight item */
        .highlight-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-bottom: 20px;
        }

        .highlight-item img {
            width: 100%;
            max-width: 200px;
            height: 130px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .highlight-item p {
            font-size: 14px;
            color: #555;
            margin: 0;
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
            width: 220px;
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
            margin-left: 40px;
        }


        /* Map Section */
  .map-page {
    display: flex;
    flex-direction: column;
    justify-content: center;   /* vertical center */
    align-items: center;       /* horizontal center */
    min-height: 100vh;         /* fill page height */
    margin: 0;
    padding: 0 20px;
}

/* Map image styling */
.map-page .map-image {
    max-width: 90%;   /* fit page width */
    max-height: 80vh; /* fit page height */
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    display: block;
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
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 5px;
                justify-items: center;
                align-items: start;
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
</head>

<body>
    <?php echo $slot ?? ''; ?>


    <!-- Or you can paste your HTML content directly here -->
    <div class="card-body">
        <div class="row mb-3">
            <div class="content-wrapper">

                <!-- Package Header -->
                <!-- Package Header for PDF -->
                <div class="package-header" style="position: relative; width: 100%; padding: 20px 10px;">

                    <!-- Top-left: Company Logo -->
                    <div style="position: absolute; top: 20px; left: 20px;">
                        <img src="<?php echo e(public_path('images/vacayguider.png')); ?>" alt="VacayGuider Logo"
                            style="height: 50px;">
                    </div>

                    <!-- Top-right: Country Badge -->
                    <div style="position: absolute; top: 25px; right: 20px; font-size: 0.5rem; color: #000; text-align: center;font-weight: 600;"
                        class="sri-lanka-badge">
                        <img src="<?php echo e(public_path('images/srilanka.png')); ?>" alt="Sri Lanka Flag"
                            style="height: 30px; display:block; margin-bottom: 5px;">
                        <span><?php echo e($package->country_name ?? 'Sri Lanka'); ?></span>
                    </div>

                    <!-- Main Heading (centered) -->
                    <h1 style="text-align: center; color: #313b5e; margin-top: 60px; font-size: 24px;">
                        <?php echo e($package->heading); ?>

                    </h1>

                    <!-- Tour Route -->
                    <?php
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
                    ?>

                    <div class="route-display">
                        <div class="route-label">Tour Route</div>
                        <div class="route-path">
                            <span class="route-point">

                                <img src="<?php echo e(public_path('images/plane-departure.svg')); ?>" alt="Plane Departure"
                                    width="16" height="16">
                                Airport
                            </span>
                            <span class="route-arrow">→</span>

                            <?php $__currentLoopData = $cityList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="route-point"><?php echo e($city); ?></span>
                                <?php if($index < count($cityList) - 1): ?>
                                    <span class="route-arrow">→</span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <span class="route-point">
                                Airport
                                <img src="<?php echo e(public_path('images/plane-departure.svg')); ?>" alt="Plane Departure"
                                    width="16" height="16">
                            </span>
                        </div>
                    </div>
                </div>


                <?php
                    $defaultImage = public_path('images/no-image.jpg');
                    $imagePath = $package->picture
                        ? public_path('storage/' . ltrim($package->picture, '/'))
                        : $defaultImage;

                    $imageData = base64_encode(file_get_contents($imagePath));
                    $mimeType = mime_content_type($imagePath);
                    $imageUrl = "data:$mimeType;base64,$imageData";
                ?>

                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($package->place); ?>" class="hero-image">


                <!-- Description Section -->
                <?php if(!empty($package->description)): ?>
                    <div class="description-section">
                        <p><?php echo e($package->description); ?></p>
                    </div>
                <?php endif; ?>

                <div class="page-break"></div>
                <div class="pdf-center-wrapper">
                    <div class="tour-summary-card" id="summary-section">
                        <div class="d-flex align-items-center mb-4">
                            <h2 class="mb-0 me-3 text-center">Tour Summary for <?php echo e($package->days ?? 0); ?> Days,
                                <?php echo e($package->nights ?? 0); ?> Nights</h2>

                        </div>


                        <p class="summary-description"><?php echo e($package->summary_description); ?></p>


                        <div class="destinations-section">
                            <h3 class="section-title">Destinations</h3>
                            <div class="destinations-list">
                                <?php
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
                                ?>

                                <?php $__currentLoopData = $cityList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span><?php echo e($city); ?></span>
                                    <?php if($index < count($cityList) - 1): ?>
                                        <span class="text-blue-600">→</span>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>


                        <!-- Themes -->


                        <div class="themes-section">
                            <h3 class="section-title">Themes</h3>
                            <div class="themes-grid">
                                <?php
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
                                ?>

                                <?php if(!empty($themeList)): ?>
                                    <div class="row ms-2 text-secondary theme-grid">
                                        <?php $__currentLoopData = $themeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
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
                                            ?>

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="<?php echo e($iconUrl); ?>" alt="<?php echo e($theme); ?> icon"
                                                        class="theme-icon me-2">
                                                    <span><?php echo e($theme); ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>





                <!-- Itineraries -->
                <?php $__currentLoopData = $package->detailItineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="itinerary-card page-break">
                        <div class="day-header">
                            <div class="day-number"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></div>
                            <div class="day-info">
                                <div class="day-label">Day <?php echo e($index + 1); ?></div>
                                <h3><?php echo e($itinerary->title ?? 'Day Activity'); ?></h3>
                            </div>
                        </div>

                        <p class="summary-description"><?php echo e($itinerary->description ?? '-'); ?></p>

                        <?php
                            $defaultImage = public_path('images/no-image.jpg');
                            $coverPath = $itinerary->pictures
                                ? public_path('storage/' . ltrim($itinerary->pictures, '/'))
                                : $defaultImage;

                            $coverData = base64_encode(file_get_contents($coverPath));
                            $coverMime = mime_content_type($coverPath);
                            $coverImage = "data:$coverMime;base64,$coverData";
                        ?>

                        <img src="<?php echo e($coverImage); ?>" alt="<?php echo e($itinerary->place_name); ?>" class="itinerary-image">



                        <!-- Program -->

                        <div class="program-section">
                            <h4>Day <?php echo e($index + 1); ?> Program</h4>
                            <div class="program-box">
                                <?php $__currentLoopData = collect($itinerary->program_points)->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="program-point"><?php echo e(trim($point)); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>



                        <!-- Highlights -->
                        <?php if($itinerary->highlights->isNotEmpty()): ?>
                            <!-- Force new page before highlights -->
                            <div style="page-break-before: always;"></div>

                            <div class="highlights-section">
                                <h3 class="section-title text-start"><?php echo e($itinerary->place_name); ?> Highlights</h3>

                                <div class="highlights-grid ">
                                    <?php $__currentLoopData = $itinerary->highlights->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $highlight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $defaultImage = public_path('images/no-image.jpg');
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
                                                    $path = $img
                                                        ? public_path('storage/' . ltrim($img, '/'))
                                                        : $defaultImage;

                                                    if (!file_exists($path)) {
                                                        $path = $defaultImage;
                                                    }

                                                    $data = base64_encode(file_get_contents($path));
                                                    $mime = mime_content_type($path);
                                                    return "data:$mime;base64,$data";
                                                })
                                                ->all();
                                        ?>

                                        <div class="highlight-item">
                                            <img src="<?php echo e($imageUrls[0] ?? ''); ?>"
                                                alt="<?php echo e($highlight->highlight_places ?? 'Highlight'); ?>">
                                            <p><?php echo e($highlight->highlight_places ?? 'Highlight'); ?></p>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Accommodation -->
                        <div class="accommodation-box">
                            <div class="accommodation-item">
                                <div class="accommodation-icon">
                                    <i class="fas fa-hotel"></i><span>Accommodation</span>
                                </div>
                                <div class="accommodation-value"><?php echo e($itinerary->overnight_stay ?? 'Not specified'); ?>

                                </div>
                            </div>
                            <div class="accommodation-item">
                                <div class="accommodation-icon">
                                    <i class="fas fa-utensils"></i><span>Meal Plan</span>
                                </div>
                                <div class="accommodation-value"><?php echo e($itinerary->meal_plan ?? 'None'); ?></div>
                            </div>
                            <div class="accommodation-item">
                                <div class="accommodation-icon">
                                    <i class="far fa-clock"></i><span>Travel Time</span>
                                </div>
                                <div class="accommodation-value"><?php echo e($itinerary->approximate_travel_time ?? 'N/A'); ?>

                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                <!-- Map Section -->
                <?php if(!empty($package->map_image)): ?>
                             <div style="page-break-before: always;"></div>
                    <?php
                        $defaultMapImage = public_path('assets/img/default-map.jpg');
                        $mapPath = $package->map_image
                            ? public_path('storage/' . ltrim($package->map_image, '/'))
                            : $defaultMapImage;

                        $mapData = base64_encode(file_get_contents($mapPath));
                        $mapMime = mime_content_type($mapPath);
                        $mapImage = "data:$mapMime;base64,$mapData";
                    ?>

                    <div class="map-page">
                        <h3>Tour Map</h3>
                        <img src="<?php echo e($mapImage); ?>" alt="Tour Route Map" class="map-image">
                    </div>
                <?php endif; ?>


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
                        <li>Meals not mentioned in the itinerary</li>
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
                    <img src="<?php echo e(public_path('images/vacayguider.png')); ?>" alt="VacayGuider Logo">
                </div>

            </div>


        </div>
    </div>
</body>

</html>
<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/tour/pdf/package.blade.php ENDPATH**/ ?>