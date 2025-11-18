<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Preferred Dates</th>
            <th>Travelers</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($requests as $req)
            <tr>
                <td>{{ $req->name }}</td>
                <td>{{ $req->email }}</td>
                <td>{{ $req->phone }}</td>
                <td>{{ $req->preferred_dates }}</td>
                <td>{{ $req->travelers }}</td>
                <td>{{ $req->message }}</td>

                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="{{ $req->id }}">
                        <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="viewed" {{ $req->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                        <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No custom tour requests found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $requests->links() }}
