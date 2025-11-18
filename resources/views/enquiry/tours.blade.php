@extends('layouts.vertical', ['subtitle' => 'Package Bookings'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Package Bookings', 'subtitle' => 'View'])

<style>
    .status-dropdown-btn {
        min-width: 110px;
        text-align: center;
    }

    .btn-equal {
        width: 80px;
        text-align: center;
    }

    .icon-btn {
        background: none;
        border: none;
        padding: 4px;
        margin: 0 2px;
        cursor: pointer;
        transition: transform 0.2s, color 0.2s;
    }

    .icon-btn:hover {
        transform: scale(1.2);
        opacity: 0.8;
        text-decoration: none;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">Package Booking List</h5>
            <p class="card-subtitle mb-0">All package bookings in your system.</p>
        </div>
    </div>

    <div class="card-body">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
<div class="row mb-3 justify-content-end">
    <div class="col-md-3">
        <label for="searchName" class="form-label">Search by Full Name</label>
        <input type="text" id="searchName" class="form-control" placeholder="Enter full name">
    </div>
</div>

        <!-- Table -->
        <div class="table-responsive" id="bookingTable">

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Street</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>WhatsApp</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Infants</th>
                        <th>Package</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Pickup</th>
                        <th>Hotel Type</th>
                        <th>Travelling From</th>
                        <th>Reason</th>
                        <th>Theme</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        <tr id="booking-{{ $booking->id }}">

                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->street }}</td>
                            <td>{{ $booking->city }}</td>
                            <td>{{ $booking->country }}</td>
                            <td>{{ $booking->email }}</td>
                            <td>{{ $booking->phone }}</td>
                            <td>{{ $booking->whatsapp }}</td>
                            <td>{{ $booking->adults }}</td>
                            <td>{{ $booking->children }}</td>
                            <td>{{ $booking->infants }}</td>
                            <td>{{ $booking->package->title ?? 'N/A' }}</td>

                            <td>{{ $booking->start_date?->format('d M Y') }}</td>
                            <td>{{ $booking->end_date?->format('d M Y') }}</td>

                            <td>{{ $booking->pickup }}</td>
                            <td>{{ $booking->hotel_type }}</td>
                            <td>{{ $booking->travelling_from }}</td>
                            <td>{{ $booking->travel_reason }}</td>

                            <td>
                                @if (is_array($booking->theme))
                                    {{ implode(', ', $booking->theme) }}
                                @else
                                    {{ $booking->theme }}
                                @endif
                            </td>

                            <td>{{ $booking->message }}</td>

                            {{-- Status Dropdown --}}
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm status-dropdown-btn dropdown-toggle
                                        @switch($booking->status)
                                            @case('pending') btn-warning @break
                                            @case('confirmed') btn-primary @break
                                            @case('completed') btn-success @break
                                            @case('cancelled') btn-danger @break
                                            @default btn-secondary
                                        @endswitch"
                                        type="button"
                                        id="statusDropdown{{ $booking->id }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ ucfirst($booking->status) }}
                                    </button>

                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $booking->id }}">
                                        @foreach(['pending','confirmed','completed','cancelled'] as $statusOption)
                                            <li>
                                                <a class="dropdown-item change-status"
                                                   href="#"
                                                   data-id="{{ $booking->id }}"
                                                   data-status="{{ $statusOption }}">
                                                    {{ ucfirst($statusOption) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bookings->links() }}

        </div>

    </div>
</div>

{{-- AJAX Status Update --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.change-status').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const bookingId = this.dataset.id;
            const newStatus = this.dataset.status;

            fetch(`/admin/enquiry/${bookingId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById(`statusDropdown${bookingId}`);
                    btn.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                    btn.className = `btn btn-sm status-dropdown-btn dropdown-toggle ${
                        newStatus === 'pending' ? 'btn-warning' :
                        newStatus === 'confirmed' ? 'btn-primary' :
                        newStatus === 'completed' ? 'btn-success' :
                        newStatus === 'cancelled' ? 'btn-danger' :
                        'btn-secondary'
                    }`;

                } else {
                    alert('Failed to update status.');
                }
            });
        });
    });
});
</script>

@endsection
