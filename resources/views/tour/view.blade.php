@extends('layouts.vertical', ['subtitle' => 'Tour Packages'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'View'])

    <style>
        .btn-equal {
            width: 80px;
            /* or any fixed width you want */
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
        <div class="card-header">
            <h5 class="card-title">Tour Package List</h5>
            <p class="card-subtitle">All tour packages in your system.</p>
        </div>

        <div class="card-body">
            {{-- Success / Error Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Filters -->
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
        <div class="col-md-3">
        <label for="filterType" class="form-label">Type</label>
        <select id="filterType" class="form-select">
            <option value="">All</option>
            @foreach ($types as $type)
                <option value="{{ $type }}" {{ ($currentFilters['type'] ?? '') === $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="filterCategory" class="form-label">Category</label>
        <select id="filterCategory" class="form-select">
            <option value="">All</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" {{ ($currentFilters['category'] ?? '') === $category ? 'selected' : '' }}>
                    {{ ucfirst($category) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterStatus" class="form-label">Status</label>
        <select id="filterStatus" class="form-select">
            <option value="">All</option>
            <option value="active" {{ ($currentFilters['status'] ?? '') === 'active' ? 'selected' : '' }}>Published</option>
            <option value="inactive" {{ ($currentFilters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Unpublished</option>
        </select>
    </div>
            </div>



            <!-- Table -->
            <div class="table-responsive" id="packageTable">
                @include('tour.table')

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('filterType');
            const categorySelect = document.getElementById('filterCategory');
            const statusSelect = document.getElementById('filterStatus');

            function fetchFilteredData(url = null) {
                let params = new URLSearchParams({
                    type: typeSelect.value,
                    category: categorySelect.value,
                    status: statusSelect.value
                });

                url = url || "{{ route('admin.packages.index') }}";
                if (url.includes('?')) {
                    url += `&${params.toString()}`;
                } else {
                    url += `?${params.toString()}`;
                }

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(res => res.text())
                    .then(data => {
                        document.getElementById('packageTable').innerHTML = data;
                        attachDeleteEvents();
                    });
            }

            // Pagination clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('#packageTable .pagination a')) {
                    e.preventDefault();
                    let url = e.target.getAttribute('href');
                    fetchFilteredData(url);
                }
            });


            typeSelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            categorySelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            statusSelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            // AJAX Pagination
            document.addEventListener('click', function(e) {
                if (e.target.closest('#packageTable .pagination a')) {
                    e.preventDefault();
                    let url = e.target.getAttribute('href');
                    fetchFilteredData(url);
                }
            });

            // Delete Package
            function attachDeleteEvents() {
                document.querySelectorAll('.delete-package').forEach(button => {
                    button.addEventListener('click', function() {
                        let packageId = this.dataset.id;

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
                                fetch("{{ url('admin/packages') }}/" + packageId, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                            'Accept': 'application/json'
                                        },
                                        credentials: 'same-origin'
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            document.getElementById('package-' +
                                                packageId).remove();
                                            Swal.fire('Deleted!', data.message,
                                                'success');
                                        } else {
                                            Swal.fire('Error!', data.message ||
                                                'Something went wrong!', 'error');
                                        }
                                    })
                                    .catch(() => Swal.fire('Error!',
                                        'Something went wrong!', 'error'));
                            }
                        });
                    });
                });
            }

            attachDeleteEvents();
        });
    </script>
@endsection
