@extends('layouts.vertical', ['subtitle' => 'Vehicle Details'])

@section('content')
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>


    <div class="w-full pb-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $vehicle->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Vehicle Details</p>
            </div>
            <a href="{{ route('admin.vehicles.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Details Cards (Left Column - Larger) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- 🚨 NEW CARD: Agent Information 🚨 --}}
                {{-- 🚨 NEW CARD: Agent / Owner Information 🚨 --}}
                @if ($vehicle->agent ?? $vehicle->user)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14h.01M12 18h.01M10 19l-2 2-2-2m12 0l-2-2-2 2M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Agent / Owner Information
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Company Name --}}
                            @if ($vehicle->agent->company_name ?? $vehicle->user->company_name)
                                <div class="p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Company Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">
                                        {{ $vehicle->agent->company_name ?? $vehicle->user->company_name }}
                                    </p>
                                </div>
                            @endif

                            {{-- Agent / Owner Name --}}
                            <div class="p-3 bg-yellow-50 rounded-lg">
                                <p class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Name</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">
                                    {{ $vehicle->agent->name ?? $vehicle->user->name }}
                                </p>
                            </div>

                            {{-- Email --}}
                            @if ($vehicle->agent->email ?? $vehicle->user->email)
                                <div class="p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Email</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">
                                        <a href="mailto:{{ $vehicle->agent->email ?? $vehicle->user->email }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                            {{ $vehicle->agent->email ?? $vehicle->user->email }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            {{-- Phone --}}
                            @if ($vehicle->agent->phone ?? $vehicle->user->phone)
                                <div class="p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Phone</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">
                                        <a href="tel:{{ $vehicle->agent->phone ?? $vehicle->user->phone }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                            {{ $vehicle->agent->phone ?? $vehicle->user->phone }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif


                {{-- Basic Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if ($vehicle->make)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Manufacturer</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $vehicle->make }}</p>
                            </div>
                        @endif
                        @if ($vehicle->model)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Model</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $vehicle->model }}</p>
                            </div>
                        @endif
                        @if ($vehicle->type)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Type</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ ucfirst($vehicle->type) }}</p>
                            </div>
                        @endif
                        @if ($vehicle->condition)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Condition</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ ucfirst($vehicle->condition) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Specifications --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Specifications
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @if ($vehicle->seats)
                            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-2xl font-bold text-blue-700">{{ $vehicle->seats }}</p>
                                <p class="text-xs text-blue-600 font-medium">Seats</p>
                            </div>
                        @endif
                        @if ($vehicle->fuel_type)
                            <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-amber-600 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                </svg>
                                <p class="text-sm font-bold text-amber-700">{{ $vehicle->fuel_type }}</p>
                                <p class="text-xs text-amber-600 font-medium">Fuel</p>
                            </div>
                        @endif
                        @if ($vehicle->transmission)
                            <div class="text-center p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-emerald-600 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <p class="text-sm font-bold text-emerald-700">{{ ucfirst($vehicle->transmission) }}</p>
                                <p class="text-xs text-emerald-600 font-medium">Transmission</p>
                            </div>
                        @endif
                        @if ($vehicle->luggage_space)
                            <div class="text-center p-4 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-violet-600 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-sm font-bold text-violet-700">{{ $vehicle->luggage_space }}</p>
                                <p class="text-xs text-violet-600 font-medium">Luggage</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Features --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Features
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @if (
                            !is_null($vehicle->air_conditioned) &&
                                !in_array($vehicle->type, ['cycle', 'electricbike', 'scooter', 'motorcycle', 'tuktuk']))
                            <div
                                class="flex items-center gap-2 p-3 rounded-lg {{ $vehicle->air_conditioned ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-50 text-gray-400' }}">
                                @if ($vehicle->air_conditioned)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-sm font-medium">AC</span>
                            </div>
                        @endif
                        @if (!is_null($vehicle->helmet))
                            <div
                                class="flex items-center gap-2 p-3 rounded-lg {{ $vehicle->helmet ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-50 text-gray-400' }}">
                                @if ($vehicle->helmet)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-sm font-medium">Helmet</span>
                            </div>
                        @endif
                          @if (
                            !is_null($vehicle->air_conditioned) &&
                                !in_array($vehicle->type, ['cycle', 'electricbike', 'scooter', 'motorcycle', 'tuktuk']))
                            <div
                                class="flex items-center gap-2 p-3 rounded-lg {{ $vehicle->first_aid_kit ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-50 text-gray-400' }}">
                                @if ($vehicle->first_aid_kit)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-sm font-medium">First Aid</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pricing & Insurance --}}
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl shadow-sm p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Price</p>
                            @if ($vehicle->price)
                                <p class="text-3xl font-bold mt-1">USD ${{ number_format($vehicle->price) }}</p>
                                <p class="text-xs text-gray-400 mt-1">per day</p>
                            @endif
                        </div>
                        @if ($vehicle->insurance_type)
                            <div class="text-right">
                                <p class="text-sm text-gray-400">Insurance</p>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 mt-1 text-sm font-medium bg-white/10 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ $vehicle->insurance_type }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Image Card (Right Column - Smaller) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @if ($vehicle->vehicle_image)
                        <img src="{{ asset('storage/' . $vehicle->vehicle_image) }}" alt="{{ $vehicle->name }}"
                            class="w-full h-64 object-cover ">
                    @else
                        <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <div class="p-4">
                        @if (!is_null($vehicle->status))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Status</span>
                                @if ($vehicle->status == 1)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sub Images --}}
                @if ($vehicle->sub_image && count($vehicle->sub_image) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mt-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Gallery</h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($vehicle->sub_image as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Vehicle image"
                                    class="w-full h-30 object-cover border rounded-lg hover:opacity-80 transition-opacity cursor-pointer">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>


        </div>
    </div>

@endsection
