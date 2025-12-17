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
                @if ($package->packageVehicles && $package->packageVehicles->isNotEmpty())
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


            </div>
        </div>
</body>

</html>
