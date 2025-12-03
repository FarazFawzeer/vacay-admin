<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company Name</th>
            <th>City</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Land Line</th>
            <th>WhatsApp</th>
            <th>Service</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($agents as $agent)
        <tr id="agent-{{ $agent->id }}">
            <td>{{ $agent->name }}</td>
            <td>{{ $agent->email }}</td>
            <td>{{ $agent->company_name ?? '-' }}</td>
            <td>{{ $agent->company_city ?? '-' }}</td>
            <td>{{ $agent->company_country ?? '-' }}</td>
            <td>{{ $agent->phone ?? '-' }}</td>
            <td>{{ $agent->land_line ?? '-' }}</td>
            <td>{{ $agent->whatsapp ?? '-' }}</td>
            <td>{{ $agent->service ?? '-' }}</td>
            <td>
                @if($agent->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>

            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.agents.edit', $agent->id) }}"
                        class="btn btn-sm p-0 text-primary border-0 bg-transparent">
                        <i class="fas fa-edit fa-lg"></i>
                    </a>
                    <button type="button"
                        class="btn btn-sm p-0 text-danger border-0 bg-transparent delete-agent"
                        data-id="{{ $agent->id }}">
                        <i class="fas fa-trash-alt fa-lg"></i>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="13" class="text-center text-muted">No agents found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<div class="d-flex justify-content-end mt-3">
    {{ $agents->links() }}
