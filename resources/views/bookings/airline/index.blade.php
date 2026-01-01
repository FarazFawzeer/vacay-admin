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
                        {{-- Fixed the "Accepted" value typo below --}}
                        <option value="Quotation" {{ request('status') == 'Quotation' ? 'selected' : '' }}>Quotation
                        </option>
                        <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="Invoiced" {{ request('status') == 'Invoiced' ? 'selected' : '' }}>Invoiced</option>
                        <option value="Partially Paid" {{ request('status') == 'Partially Paid' ? 'selected' : '' }}>
                            Partially Paid</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="searchInvoice" class="form-label">Invoice No</label>
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Search Invoice No"
                        value="{{ request('inv_no') }}">
                </div>
            </div>
            <!-- Table -->
            <div id="bookingTable" class="table-responsive">
                @include('bookings.airline.table')
            </div>

        </div>
    </div>

    <script>
        function initTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initTooltips();
        });
    </script>
    {{-- AJAX Filter + Pagination --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusSelect = document.getElementById('filterStatus');
            const searchInput = document.getElementById('searchInvoice');
            const tableContainer = document.getElementById('bookingTable');

            // Re-init tooltips after AJAX content swap
            function initTooltips() {
                var tooltipTriggerList = [].slice.call(tableContainer.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Main Fetch Function
            function fetchFilteredData(url = null) {
                // 1. Get the base URL
                let baseUrl = "{{ route('admin.airline-bookings.index') }}";

                // 2. Create a URL object
                let fetchUrl = url ? new URL(url) : new URL(baseUrl);

                // 3. Get values directly from the elements to ensure they are fresh
                const statusValue = document.getElementById('filterStatus').value;
                const invValue = document.getElementById('searchInvoice').value;

                // 4. Set/Update parameters
                if (statusValue) {
                    fetchUrl.searchParams.set('status', statusValue);
                } else {
                    fetchUrl.searchParams.delete('status');
                }

                if (invValue) {
                    fetchUrl.searchParams.set('inv_no', invValue);
                } else {
                    fetchUrl.searchParams.delete('inv_no');
                }

                // 5. Perform the fetch
                fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        initTooltips();

                        // Optional: Update the browser's address bar URL without reloading
                        window.history.pushState({}, '', fetchUrl);
                    })
                    .catch(err => console.error('Error:', err));
            }
            // SINGLE EVENT LISTENER (Event Delegation)
            // This handles Pagination, Status, and Delete for current AND future rows
            tableContainer.addEventListener('click', function(e) {

                // 1. AJAX Pagination
                const paginationLink = e.target.closest('.pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    fetchFilteredData(paginationLink.href);
                    return;
                }

                // 2. AJAX Status Change
                const statusBtn = e.target.closest('.change-status');
                if (statusBtn) {
                    e.preventDefault();
                    const id = statusBtn.dataset.id;
                    const status = statusBtn.dataset.status;

                    fetch(`/admin/airline-bookings/${id}/status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Refresh table to update button colors and text
                                fetchFilteredData();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Status Updated',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    return;
                }

                // 3. AJAX Delete
                const deleteBtn = e.target.closest('.delete-booking');
                if (deleteBtn) {
                    const id = deleteBtn.dataset.id;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Delete this booking permanently?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/airline-bookings/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        fetchFilteredData(); // Refresh to update pagination
                                        Swal.fire('Deleted!', data.message, 'success');
                                    }
                                });
                        }
                    });
                }
            });

            // Filter Change Listeners
            statusSelect.addEventListener('change', () => fetchFilteredData());

            let debounceTimer;
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchFilteredData(), 500);
            });

            // Initial tooltips
            initTooltips();
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
