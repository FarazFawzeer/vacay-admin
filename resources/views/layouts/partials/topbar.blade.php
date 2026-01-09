<header class="app-topbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center gap-2">
                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu topbar-button">
                        <iconify-icon icon="solar:hamburger-menu-outline" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <!-- App Search-->
                {{-- <form class="app-search d-none d-md-block me-auto">
                         <div class="position-relative">
                              <input type="search" class="form-control" placeholder="admin,widgets..."
                                   autocomplete="off" value="">
                              <iconify-icon icon="solar:magnifer-outline" class="search-widget-icon"></iconify-icon>
                         </div>
                    </form> --}}
            </div>

            <div class="d-flex align-items-center gap-2">

                @if (Auth::user()?->type === 'Super Admin')
                    <!-- Online Users -->
                    <div class="dropdown topbar-item">
                        <button type="button" class="topbar-button position-relative" data-bs-toggle="dropdown">
                            <iconify-icon icon="solar:users-group-rounded-outline"
                                class="fs-22 align-middle"></iconify-icon>

                            @if ($topOnlineUserCount > 0)
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge bg-success rounded-pill">
                                    {{ $topOnlineUserCount }}
                                </span>
                            @endif
                        </button>

                        <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end">
                            <div class="p-2 border-bottom bg-light">
                                <h6 class="m-0 fs-16 fw-semibold">
                                    Online Users ({{ $topOnlineUserCount }})
                                </h6>
                            </div>

                            <div data-simplebar style="max-height: 250px;">
                                @forelse($topOnlineUsers as $user)
                                    <div class="dropdown-item p-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm me-2">
                                                    <span
                                                        class="avatar-title rounded-circle bg-soft-success text-success">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium">{{ $user->name }}</p>
                                                <small class="text-muted">
                                                    {{ ucwords(str_replace('_', ' ', $user->type)) }}
                                                </small>
                                            </div>
                                            <span class="badge bg-success ms-2">Online</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-3 text-muted">
                                        No active users
                                    </div>
                                @endforelse
                            </div>

                            <div class="text-center p-2">
                                <a href="{{ route('admin.users.online') }}" class="btn btn-success btn-sm">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                @endif



                @if (Auth::user()?->type === 'Super Admin')
                    <!-- Notification -->
                    <div class="dropdown topbar-item">
                        <button type="button" class="topbar-button position-relative" data-bs-toggle="dropdown">
                            <iconify-icon icon="solar:bell-bing-outline" class="fs-22 align-middle"></iconify-icon>

                            @if ($topReminderCount > 0)
                                <span
                                    class="position-absolute  topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">
                                    {{ $topReminderCount }}
                                </span>
                            @endif
                        </button>

                        <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end">
                            <div class="p-2 border-bottom bg-light">
                                <h6 class="m-0 fs-16 fw-semibold">
                                    Reminder Notifications ({{ $topReminderCount }})
                                </h6>
                            </div>

                            <div data-simplebar style="max-height: 250px;">
                                @forelse($topReminders as $reminder)
                                    <a href="{{ route('admin.reminders.edit', $reminder->id) }}"
                                        class="dropdown-item p-2 border-bottom">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm me-2">
                                                    <span class="avatar-title  text-dark rounded-circle">⏰</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium">{{ $reminder->title }}</p>
                                                <small class="text-muted">Due:
                                                    {{ $reminder->due_date->format('d M Y, h:i A') }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center p-3 text-muted">No reminders due!</div>
                                @endforelse
                            </div>

                            <div class="text-center p-2">
                                <a href="{{ route('admin.reminders.index') }}" class="btn btn-primary btn-sm">
                                    View All\
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Theme Color (Light/Dark) -->
                <div class="topbar-item">
                    <button type="button" class="topbar-button" id="light-dark-mode">
                        <iconify-icon icon="solar:moon-outline" class="fs-22 align-middle light-mode"></iconify-icon>
                        <iconify-icon icon="solar:sun-2-outline" class="fs-22 align-middle dark-mode"></iconify-icon>
                    </button>
                </div>
                <!-- User -->
                <div class="dropdown topbar-item">
                    <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle" width="32"
                                src="{{ Auth::user()?->image_path ? asset(Auth::user()->image_path) : asset('images/users/avatar-6.jpg') }}"
                                alt="avatar-3">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome!</h6>

                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                            <iconify-icon icon="solar:user-outline" class="align-middle me-2 fs-18"></iconify-icon><span
                                class="align-middle">My
                                Account</span>
                        </a>


                        <a class="dropdown-item" href="{{ route('second', ['auth', 'lock-screen']) }}">
                            <iconify-icon icon="solar:lock-keyhole-outline"
                                class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Lock
                                screen</span>
                        </a>

                        <div class="dropdown-divider my-1"></div>

                        <a class="dropdown-item text-danger" href="{{ route('second', ['auth', 'signin']) }}">
                            <iconify-icon icon="solar:logout-3-outline"
                                class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
