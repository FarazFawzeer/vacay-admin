<body>

    @if ($section === 'full')
        @include('tour.pdf.package')
    @endif

    @if ($section === 'header')
        @include('tour.pdf.sections.header')
    @endif

    @if ($section === 'summary')
        @include('tour.pdf.sections.summary')
    @endif

    @if ($section === 'itinerary')
        @include('tour.pdf.sections.itinerary')
    @endif

    @if ($section === 'map' && !empty($package->map_image))
        @include('tour.pdf.sections.map')
    @endif

    @if ($section === 'vehicle' && $package->packageVehicles->isNotEmpty())
        @include('tour.pdf.sections.vehicle')
    @endif

    @if ($section === 'inclusion')
        @include('tour.pdf.sections.inclusion')
    @endif


    @if ($section === 'hilights')
        @include('tour.pdf.sections.hilights')
    @endif
</body>
