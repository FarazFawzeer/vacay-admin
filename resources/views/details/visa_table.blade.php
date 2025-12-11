<div class="table-responsive">
    <table class="table table-hover table-centered align-middle">
        <thead class="table-light">
            <tr>
                <th>From → To</th>
                <th>Visa Type</th>
                <th>Categories</th>
                <th>Agent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visas as $visa)
                <tr>
                    {{-- From → To --}}
                    <td>{{ $visa->from_country }} → {{ $visa->to_country }}</td>

                    {{-- Visa Type --}}
                    <td>
                        {{ $visa->visa_type }}
                        @if ($visa->custom_visa_type)
                            <br><small>({{ $visa->custom_visa_type }})</small>
                        @endif
                    </td>

                    {{-- Categories --}}
                    <td>
                        @if ($visa->categories && $visa->categories->count() > 0)
                            <ul class="mb-0">
                                @foreach ($visa->categories as $cat)
                                    <li>
                                        {{ $cat->visa_type ?? 'N/A' }}
                                        @if ($cat->state)
                                            - {{ $cat->state }}
                                        @endif
                                        @if ($cat->days)
                                            ({{ $cat->days }} Days)
                                        @endif
                                        @if ($cat->price)
                                            - {{ $cat->price }} {{ $cat->currency ?? '' }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            N/A
                        @endif
                    </td>


                    <td>
                        @if ($visa->agents && $visa->agents->count() > 0)
                            @foreach ($visa->agents as $agent)
                                <span class="badge bg-primary mb-1">{{ $agent->company_name }} -
                                    {{ $agent->name }}</span><br>
                            @endforeach
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>




                    {{-- Actions --}}
                    <td class="text-center">

                        <!-- Show/View Icon -->
                        <a href="{{ route('admin.visa.show', $visa->id) }}" class="icon-btn text-info btn-info"
                            title="View">
                            <i class="bi bi-eye-fill fs-5"></i>
                        </a>


                        <!-- Edit Icon -->
                        <button type="button" class="icon-btn  text-primary editVisaBtn" data-id="{{ $visa->id }}"
                            title="Edit">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </button>



                        <!-- Delete Icon -->
                        <form action="{{ route('admin.visa.destroy', $visa->id) }}" method="POST"
    class="d-inline-block deleteVisaForm" title="Delete">
    @csrf
    @method('DELETE')
    <button type="submit" class="icon-btn text-danger">
        <i class="bi bi-trash-fill fs-5"></i>
    </button>
</form>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No visas found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $visas->links() }}
    </div>
</div>
