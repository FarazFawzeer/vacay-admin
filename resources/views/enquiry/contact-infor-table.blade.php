<div class="table-responsive" style="overflow-x:auto;">
<table class="table table-striped table-hover table-sm text-nowrap">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Country</th>
            <th>Service</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @forelse($contacts as $contact)
    <tr>
        <td>{{ $contact->name }}</td>
        <td>{{ $contact->email }}</td>
        <td>{{ $contact->phone }}</td>
        <td>{{ $contact->country }}</td>
        <td>{{ $contact->service }}</td>
        <td>{{ $contact->message }}</td>
        <td>
            <select class="form-select form-select-sm changeStatus" data-id="{{ $contact->id }}">
                <option value="pending" {{ $contact->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="viewed" {{ $contact->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                <option value="completed" {{ $contact->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center">No contact inquiries found.</td>
    </tr>
    @endforelse
    </tbody>
</table>
</div>

{{ $contacts->links() }}
