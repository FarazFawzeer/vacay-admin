@extends('layouts.vertical', ['subtitle' => 'Online Users'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Online Users',
        'subtitle' => 'Currently Logged-in Admin Staff',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($onlineUsers->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($onlineUsers as $index => $user)
                                        @php
                                            $lastActivity = isset($sessions[$user->id]) 
                                                ? $sessions[$user->id]->last_activity 
                                                : null;
                                        @endphp
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <span class="fw-medium">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-muted">{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ ucwords(str_replace('_', ' ', $user->type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="mdi mdi-circle font-size-10 me-1"></i>Online
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="mdi mdi-account-off-outline font-size-48 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Active Users</h5>
                            <p class="text-muted mb-0">There are currently no logged-in admin staff members.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection