<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Hotel Name</th>
            <th>Category</th>
            <th>Country</th>
            <th>City</th>
            <th>Star</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($hotels as $hotel)
            <tr id="hotel-{{ $hotel->id }}">
                <td>{{ $hotel->hotel_name }}</td>
                <td>{{ ucfirst($hotel->hotel_category) ?? '-' }}</td>
                <td>{{ $hotel->country ?? '-' }}</td>
                <td>{{ $hotel->city ?? '-' }}</td>
                <td>{{ $hotel->star ? $hotel->star . ' ★' : '-' }}</td>





                <td>
                    @if ($hotel->status == 1)
                        <span class="badge bg-success">Active</span>
                    @elseif ($hotel->status == 0)
                        <span class="badge bg-danger">Inactive</span>
                    @elseif ($hotel->status == 2)
                        <span class="badge bg-warning text-dark">Expire</span>
                    @endif
                </td>



                <td>

                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="icon-btn text-info" title="View Details">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    <button type="button" class="icon-btn text-primary edit-hotel" data-id="{{ $hotel->id }}"
                        data-name="{{ $hotel->hotel_name }}" data-star="{{ $hotel->star }}"
                        data-category="{{ $hotel->hotel_category }}" data-room_type='@json($hotel->room_type)'
                        data-meal_plan="{{ $hotel->meal_plan }}" data-meal_costs='@json($hotel->meal_costs)'
                        data-description="{{ $hotel->description }}" data-facilities='@json($hotel->facilities)'
                        data-entertainment='@json($hotel->entertainment)' data-status="{{ $hotel->status }}"
                        data-city="{{ $hotel->city }}" data-address="{{ $hotel->address }}"
                        data-country="{{ $hotel->country }}" data-pictures='@json($hotel->pictures)'
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
