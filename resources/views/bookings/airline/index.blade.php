@extends('layouts.vertical', ['subtitle' => 'Airline Bookings'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Airline Bookings',
        'subtitle' => 'View',
    ])

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Airline Booking List</h5>
                <p class="card-subtitle mb-0">All airline bookings in your system.</p>
            </div>

            <div>
                <a href="{{ route('admin.airline-bookings.create') }}" class="btn btn-primary">
                    Add Booking
                </a>
            </div>
        </div>

        <div class="card-body">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="Quotation" selected>Quotation</option>
                        <option value="Accepted<">Accepted</option>
                        <option value="Invoiced">Invoiced</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Paid">Paid</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="searchInvoice" class="form-label">Invoice No</label>
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Search Invoice No">
                </div>
            </div>

            <!-- Table -->
            <div id="bookingTable" class="table-responsive">
                @include('bookings.airline.table')
            </div>

        </div>
    </div>

    {{-- AJAX Filter + Pagination --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusSelect = document.getElementById('filterStatus');
            const searchInput = document.getElementById('searchInvoice');
            const tableContainer = document.getElementById('bookingTable');

            function fetchFilteredData(url = null) {
                url = url ? url.toString() : "{{ route('admin.airline-bookings.index') }}";

                const params = new URLSearchParams({
                    status: statusSelect.value,
                    inv_no: searchInput.value
                });

                url += (url.includes('?') ? '&' : '?') + params.toString();

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(data => tableContainer.innerHTML = data)
                    .catch(err => console.error('Error fetching data:', err));
            }

            statusSelect.addEventListener('change', () => fetchFilteredData());
            searchInput.addEventListener('input', () => fetchFilteredData());

            // AJAX Pagination
            tableContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    fetchFilteredData(link.href);
                }
            });
        });
    </script>

    {{-- Status Update Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.getElementById('bookingTable');

            tableContainer.addEventListener('click', function(e) {
                const target = e.target.closest('.change-status');
                if (!target) return;

                e.preventDefault();

                const bookingId = target.dataset.id;
                const newStatus = target.dataset.status;

                fetch(`/admin/airline-bookings/${bookingId}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const btn = document.getElementById(`statusDropdown${bookingId}`);
                            btn.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        } else {
                            alert(data.message || 'Failed to update status');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error updating status.');
                    });
            });
        });
    </script>

    {{-- Delete Booking --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-booking').forEach(btn => {
                btn.addEventListener('click', function() {
                    const bookingId = this.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This booking will be deleted permanently!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/airline-bookings/${bookingId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        const row = document.getElementById(
                                            `booking-${bookingId}`);
                                        if (row) row.remove();

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: data.message,
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    } else {
                                        Swal.fire('Error', data.message ||
                                            'Failed to delete booking.', 'error');
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    Swal.fire('Error', 'Something went wrong.',
                                        'error');
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection
