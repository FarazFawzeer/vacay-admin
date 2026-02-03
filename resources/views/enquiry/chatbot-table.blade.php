<div class="table-responsive" style="overflow-x:auto;">
<table class="table table-striped table-hover table-sm text-nowrap">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    @forelse($leads as $lead)
        <tr>
            <td>{{ $lead->name }}</td>
            <td>{{ $lead->email }}</td>
            <td>{{ $lead->phone }}</td>
            <td>{{ $lead->service }}</td>
            <td>{{ $lead->created_at->format('d M Y') }}</td>
            <td>
                <select class="form-select form-select-sm changeStatus" data-id="{{ $lead->id }}">
                    <option value="pending" {{ $lead->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $lead->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $lead->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </td>
                 <td class="text-center">
                        <button type="button" class="icon-btn text-danger delete-row" data-id="{{ $lead->id }}"
                            data-url="{{ route('admin.enquiry.chatbot.delete', $lead->id) }}" title="Delete">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </td>

        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No chatbot leads found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>

{{ $leads->links() }}
