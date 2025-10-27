<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Name</th>
            <th>Source</th>
            <th>Posted On</th>
            <th>Rating</th>
            <th>Message</th>
            <th>Image</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($testimonials as $testimonial)
            <tr id="testimonial-{{ $testimonial->id }}">
                <td>{{ $testimonial->name }}</td>
                <td>{{ $testimonial->source ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($testimonial->postedate)->format('d M Y') }}</td>
                <td>{{ $testimonial->rating ?? '-' }}</td>
                <td>{{ Str::limit($testimonial->message, 50) }}</td>
                <td>
                    @if ($testimonial->image)
                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Customer Image">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $testimonial->status ? 'Active' : 'Inactive' }}</td>
                <td class="text-center">
                    {{-- Edit --}}
                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="icon-btn text-primary" title="Edit">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    {{-- Toggle Status --}}
                    <button type="button" data-id="{{ $testimonial->id }}" class="icon-btn {{ $testimonial->status ? 'text-success' : 'text-warning' }} toggle-status" data-status="{{ $testimonial->status }}">
                        @if ($testimonial->status)
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        @else
                            <i class="bi bi-slash-circle fs-5"></i>
                        @endif
                    </button>

                    {{-- Delete --}}
                    <button type="button" data-id="{{ $testimonial->id }}" class="icon-btn text-danger delete-testimonial" title="Delete">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No testimonials found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $testimonials->links() }}
