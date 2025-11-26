<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Hotel Name</th>
            <th>City</th>
            <th>Star</th>
            <th>Category</th>
            <th>Room Type</th>
            <th>Meal Plan</th>
            <th>Facilities</th>
            <th>Entertainment</th>
            <th>Pictures</th>
            <th>Status</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($hotels as $hotel)
            <tr id="hotel-{{ $hotel->id }}">
                <td>{{ $hotel->hotel_name }}</td>
                <td>{{ $hotel->city ?? '-' }}</td>
                <td>{{ $hotel->star ? $hotel->star . ' ★' : '-' }}</td>
                <td>{{ ucfirst($hotel->hotel_category) ?? '-' }}</td>
                <td>{{ $hotel->room_type ?? '-' }}</td>
                <td>{{ $hotel->meal_plan ?? '-' }}</td>
                <td>{{ Str::limit($hotel->facilities, 30) ?? '-' }}</td>
                <td>{{ Str::limit($hotel->entertainment, 30) ?? '-' }}</td>
                <td>
                    @if ($hotel->pictures)
                        @foreach ($hotel->pictures as $pic)
                            <img src="{{ asset($pic) }}" width="40" height="40" class="rounded me-1 view-image"
                                data-src="{{ asset($pic) }}" data-all='@json($hotel->pictures)'>
                        @endforeach
                    @else
                        <span class="text-muted">No images</span>
                    @endif
                </td>

                <td>
                    @if ($hotel->status)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td>{{ $hotel->updated_at->format('d M Y, h:i A') }}</td>
                <td>
                    <button type="button" class="icon-btn text-primary edit-hotel" data-id="{{ $hotel->id }}"
                        data-name="{{ $hotel->hotel_name }}" data-star="{{ $hotel->star }}"
                        data-category="{{ $hotel->hotel_category }}" data-room_type="{{ $hotel->room_type }}"
                        data-meal_plan="{{ $hotel->meal_plan }}" data-description="{{ $hotel->description }}"
                        data-facilities="{{ $hotel->facilities }}" data-entertainment="{{ $hotel->entertainment }}"
                        data-status="{{ $hotel->status }}" data-city="{{ $hotel->city }}"
                        data-address="{{ $hotel->address }}" data-pictures='@json($hotel->pictures)'
                        title="Edit Hotel">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </button>


                    <button type="button" class="icon-btn text-danger delete-hotel" data-id="{{ $hotel->id }}"
                        title="Delete Hotel">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="12" class="text-center text-muted">No hotels found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $hotels->links() }}
</div>
