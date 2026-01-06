<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Program Points</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($destinations as $destination)
            <tr id="destination-{{ $destination->id }}">
                <td>{{ $destination->name }}</td>
                <td>
                    @if (is_array($destination->program_points))
                        <ul class="mb-0">
                            @foreach ($destination->program_points as $point)
                                <li>{{ $point['point'] ?? '-' }}</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $destination->updated_at->format('d M Y, h:i A') }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <button type="button"
                            class="btn btn-sm p-0 text-info edit-destination"
                            data-id="{{ $destination->id }}"
                            data-name="{{ $destination->name }}"
                            data-points='@json($destination->program_points)'>
                            <i class="fas fa-edit fa-lg"></i>
                        </button>

                        <button type="button"
                            class="btn btn-sm p-0 text-danger delete-destination"
                            data-id="{{ $destination->id }}">
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No destinations found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $destinations->links() }}
</div>
