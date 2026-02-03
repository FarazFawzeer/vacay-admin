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
                <p class="card-subtitle mb-0">All airline ticket enquiries submitted by customers.</p>
            </div>
        </div>

        <div class="card-body">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Search --}}
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label class="form-label">Search by Name</label>
                    <input type="text" id="searchName" class="form-control" placeholder="Enter full name">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive" id="airTicketTable">
                @include('enquiry.air-ticket-table', ['bookings' => $bookings])
            </div>

        </div>
    </div>

    {{-- AJAX Search and Status Update --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search
            const searchInput = document.getElementById('searchName');
            searchInput.addEventListener('keyup', function() {
                const search = this.value;
                fetch(`{{ route('admin.enquiry.airTicket') }}?name=${search}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('airTicketTable').innerHTML = html;
                    });
            });

            // Status Update
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('changeStatus')) {
                    const id = e.target.dataset.id;
                    const status = e.target.value;

                    fetch("{{ route('admin.enquiry.airTicket.updateStatus') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id,
                                status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: 'Status updated successfully.',
                                    timer: 1300,
                                    showConfirmButton: false
                                });
                            }
                        });
                }
            });

        });
    </script>
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-row');
            if (!btn) return;

            const url = btn.dataset.url;
            const row = btn.closest('tr'); // ✅ current row

            Swal.fire({
                title: 'Are you sure?',
                text: "This record will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (!result.isConfirmed) return;

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {

                            // ✅ Remove row immediately
                            if (row) row.remove();

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Deleted successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                        } else {
                            Swal.fire('Error', 'Failed to delete.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Something went wrong.', 'error'));
            });
        });
    </script>
@endsection
