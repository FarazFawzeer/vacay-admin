<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Destination</th>
            <th>Place Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($highlights as $highlight)
            <tr id="highlight-{{ $highlight->id }}">
                <td>{{ $highlight->destination->name ?? '-' }}</td>
                <td>{{ $highlight->place_name }}</td>
                <td>{{ $highlight->description ?? '-' }}</td>
                <td>
                    @if ($highlight->image)
                        <img src="{{ asset('admin/storage/' . $highlight->image) }}" width="80">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $highlight->updated_at->format('d M Y, h:i A') }}</td>
                <td class="text-center">
                    <button type="button"
                        class="icon-btn text-primary edit-highlight"
                        data-id="{{ $highlight->id }}"
                        data-destination="{{ $highlight->destination_id }}"
                        data-place="{{ $highlight->place_name }}"
                        data-description="{{ $highlight->description }}">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </button>

                    <button type="button"
                        class="icon-btn text-danger delete-highlight"
                        data-id="{{ $highlight->id }}">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No highlights found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $highlights->links() }}
</div>
