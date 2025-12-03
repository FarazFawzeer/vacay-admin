@extends('layouts.vertical', ['subtitle' => 'Agent View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Agent', 'subtitle' => 'View'])

<style>
/* Scrollable table wrapper */
#agentTableWrapper {
    max-height: calc(100vh - 250px); /* Adjust 250px for header, filters, and pagination */
    overflow-y: auto;
}

/* Sticky header */
#agentTable thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
}
</style>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Agent List</h5>
        <p class="card-subtitle">All agents in your system with details.</p>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3 justify-content-end">
            <div class="col-md-3">
                <label for="filterStatus" class="form-label">Status</label>
                <select id="filterStatus" class="form-select">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" id="searchField" class="form-control" placeholder="Search by name or email">
            </div>

       
        </div>

        <!-- Table -->
        <div class="table-responsive table-wrapper" style="max-height: calc(100vh - 250px); overflow-y: auto;" id="agentTable">
            @include('agent.index-table')
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('filterStatus');
    const searchField = document.getElementById('searchField');
    const agentTable = document.getElementById('agentTable');

    // Fetch and update table
    function fetchFilteredData(url = null) {
        // Ensure url is a string
        if (!url || typeof url !== 'string') {
            url = "{{ route('admin.agents.index') }}";
        }

        let status = statusSelect.value;
        let search = searchField.value;

        const separator = url.includes('?') ? '&' : '?';
        url += `${separator}status=${status}&search=${encodeURIComponent(search)}`;

        fetch(url, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
            .then(res => res.text())
            .then(data => { agentTable.innerHTML = data; });
    }

    // Correctly attach event listeners
    statusSelect.addEventListener('change', function() { fetchFilteredData(); });
    searchField.addEventListener('keyup', function() { fetchFilteredData(); });

    // Pagination AJAX
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#agentTable .pagination a');
        if (!paginationLink) return;

        e.preventDefault();
        const url = paginationLink.getAttribute('href');
        fetchFilteredData(url);
    });

    // Delete agent
    agentTable.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-agent');
        if (!deleteBtn) return;

        const agentId = deleteBtn.dataset.id;

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
                fetch("{{ url('admin/agents') }}/" + agentId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('agent-' + agentId);
                        if (row) row.remove();
                        Swal.fire('Deleted!', data.message, 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
            }
        });
    });
});

</script>
@endsection
