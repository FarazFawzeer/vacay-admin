<div class="table-responsive">
    <table class="table table-hover table-centered">
        <thead class="table-light">
            <tr>
                <th>Country</th>
                <th>Visa Type</th>
                <th>Visa Details</th>
                <th>Documents</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visas as $visa)
                <tr id="visa-{{ $visa->id }}">
                    <td>{{ $visa->country }}</td>
                    <td>{{ $visa->visa_type }}</td>
                    <td>{{ Str::limit($visa->visa_details, 40) ?? '-' }}</td>
                    <td>
                        @if ($visa->documents)
                            <img src="{{ asset('admin/storage/' . $visa->documents) }}" width="50" height="50"
                                class="rounded">
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.visa.show', $visa->id) }}" class="icon-btn text-info"><i
                                class="bi bi-eye fs-5"></i></a>
                        <button type="button" class="icon-btn text-primary edit-visa" data-id="{{ $visa->id }}"
                            data-country="{{ $visa->country }}" data-type="{{ $visa->visa_type }}"
                            data-details="{{ $visa->visa_details }}" data-documents="{{ $visa->documents }}"
                            data-note="{{ $visa->note }}"
                            data-agents="{{ $visa->agents->pluck('id')->implode(',') }}"> <i
                                class="bi bi-pencil-square fs-5"></i></button>
                        <button type="button" class="icon-btn text-danger delete-visa" ...><i
                                class="bi bi-trash-fill fs-5"></i></button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No visas found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        {{ $visas->links() }}
    </div>
</div>
