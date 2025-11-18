<div class="table-responsive" style="overflow-x:auto;">
    <table class="table table-striped table-hover table-sm text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>License No</th>
                <th>WhatsApp</th>
                <th>Collection Method</th>
                <th>License Front</th>
                <th>License Back</th>
                <th>Selfie</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->guest_name }}</td>
                    <td>{{ $request->email }}</td>
                    <td>{{ $request->license_no }}</td>
                    <td>{{ $request->whatsapp }}</td>
                    <td>{{ ucfirst($request->collection_method) }}</td>
                    <td>
                        @if ($request->license_front)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-src="{{ config('app.fe_domain') }}/{{ ltrim($request->license_front, '/') }}">
                                View
                            </a>
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        @if ($request->license_back)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-src="{{ config('app.fe_domain') }}/{{ ltrim($request->license_back, '/') }}">
                                View
                            </a>
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        @if ($request->selfie)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-src="{{ config('app.fe_domain') }}/{{ ltrim($request->selfie, '/') }}">
                                View
                            </a>
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        <select class="form-select form-select-sm changeStatus" data-id="{{ $request->id }}">
                            <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="viewed" {{ $request->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                            <option value="completed" {{ $request->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No driving permit requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $requests->links() }}

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const src = button.getAttribute('data-src');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
        });
    });
</script>
