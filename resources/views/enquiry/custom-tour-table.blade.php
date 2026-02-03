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
            <th>Action</th>
            <th>Date</th>
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
                <td>{{ $req->created_at->format('d M Y') }}</td>

                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="{{ $req->id }}">
                        <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="viewed" {{ $req->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                        <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </td>
                     <td class="text-center">
                        <button type="button" class="icon-btn text-danger delete-row" data-id="{{ $req->id }}"
                            data-url="{{ route('admin.enquiry.customTour.delete', $req->id) }}" title="Delete">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
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
