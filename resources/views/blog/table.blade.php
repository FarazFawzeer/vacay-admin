<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Posted On</th>
            <th>Likes</th>
            <th>Hashtags</th>
            <th>Image</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($blogs as $blog)
            <tr id="blog-{{ $blog->id }}">
                <td>{{ $blog->title }}</td>
                <td>{{ $blog->type ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($blog->posted_time)->format('d M Y, h:i A') }}</td>
                <td>{{ $blog->likes_count ?? 0 }}</td>
                <td>
                    @if (!empty($blog->hashtags))
                        @foreach ($blog->hashtags as $tag)
                            <span class="badge bg-light text-dark me-1">#{{ $tag }}</span>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if (!empty($blog->image_post) && is_array($blog->image_post))
                        <img src="{{ asset('storage/' . $blog->image_post[0]) }}" alt="Blog Image">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $blog->status ? 'Published' : 'Unpublished' }}</td>
                <td class="text-center">
                   
                    {{-- Edit --}}
                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="icon-btn text-primary" title="Edit Blog">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    {{-- Toggle Status --}}
                    <button type="button" data-id="{{ $blog->id }}"
                        class="icon-btn {{ $blog->status ? 'text-success' : 'text-warning' }} toggle-status"
                        data-status="{{ $blog->status }}">
                        @if ($blog->status)
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        @else
                            <i class="bi bi-slash-circle fs-5"></i>
                        @endif
                    </button>

                    {{-- Delete --}}
                    <button type="button" data-id="{{ $blog->id }}" class="icon-btn text-danger delete-blog"
                        title="Delete Blog">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No blog posts found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $blogs->links() }}
