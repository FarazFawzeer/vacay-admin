@extends('layouts.vertical', ['subtitle' => 'Customer View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'View'])


    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Customer List</h5>
            <p class="card-subtitle">All customers in your system with details.</p>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterType" class="form-label">Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">All</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-none" id="serviceFilterWrapper">
                    <label for="filterService" class="form-label">Service</label>
                    <select id="filterService" class="form-select">
                        <option value="">All</option>
                        @foreach ($services as $service)
                            <option value="{{ $service }}">{{ $service }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterHeardUs" class="form-label">Heard Us</label>
                    <select id="filterHeardUs" class="form-select">
                        <option value="">All</option>
                        @foreach ($heard_us_list as $heard)
                            <option value="{{ $heard }}">{{ $heard }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="customerTable">
                @include('customer.index-table') {{-- this will be returned also in AJAX --}}
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('filterType');
    const serviceWrapper = document.getElementById('serviceFilterWrapper');
    const serviceSelect = document.getElementById('filterService');
    const heardUsSelect = document.getElementById('filterHeardUs');
    const customerTable = document.getElementById('customerTable');

    // Fetch and update table data
    function fetchFilteredData(url = null) {
        let type = typeSelect.value;
        let service = serviceSelect.value;
        let heard_us = heardUsSelect.value;

        url = url || "{{ route('admin.customers.index') }}";

        // Append query parameters
        const separator = url.includes('?') ? '&' : '?';
        url += `${separator}type=${type}&service=${service}&heard_us=${heard_us}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(data => {
                customerTable.innerHTML = data;

                // Scroll to top of page or card
                window.scrollTo(0, 0);
                const card = customerTable.closest('.card');
                if (card) card.scrollIntoView({ behavior: 'smooth' });
            });
    }

    // Filters
    typeSelect.addEventListener('change', function() {
        if (this.value.toLowerCase() === 'corporate') {
            serviceWrapper.classList.remove('d-none');
        } else {
            serviceWrapper.classList.add('d-none');
            serviceSelect.value = '';
        }
        fetchFilteredData();
    });

    serviceSelect.addEventListener('change', fetchFilteredData);
    heardUsSelect.addEventListener('change', fetchFilteredData);

    // Pagination AJAX
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#customerTable .pagination a');
        if (paginationLink) {
            e.preventDefault();
            let url = paginationLink.getAttribute('href');
            fetchFilteredData(url);
        }
    });

    // Delete customer using event delegation
    customerTable.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-customer');
        if (!deleteBtn) return;

        const customerId = deleteBtn.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('admin/customers') }}/" + customerId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('customer-' + customerId);
                        if (row) row.remove();
                        Swal.fire('Deleted!', data.message, 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                });
            }
        });
    });
});
</script>

@endsection
