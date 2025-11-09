<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagan Matrimonials - Admin Dashboard</title>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Other libraries -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('js/ckeditor_config.js') }}"></script>
    <script src="{{ asset('js/ckeditor_init.js') }}"></script>
    <!-- Common JS file -->
    <script src="{{ asset('js/common.js') }}"></script>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @yield('css')

    <style>
        .cke_notification {
            display: none !important;
        }
    </style>
</head>

<body>
    <!-- Admin Dashboard -->
    <div class="dashboard" id="adminDashboard">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay"></div>
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">LM</div>
                    <span>Lagan Matrimonials</span>
                </div>
            </div>

            @include('layouts.admin.sidebar')
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="sidebar-toggle">
                        <i data-lucide="menu"></i>
                    </button>
                    {{--<div class="search-container">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search properties, bookings...">
                    </div>--}}
                </div>
                <div class="header-right">
                    {{--<div class="notification-btn" role="button" onclick="toggleNotificationDropdown()">
                        <i data-lucide="bell"></i>
                        <span class="notification-dot" id="notificationDot"></span>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h3>Notifications</h3>
                                <span class="mark-read" onclick="markAllRead()">Mark all read</span>
                            </div>
                            <div class="notification-list" id="notificationList">
                                <!-- Sample notifications for demo -->
                                <div class="notification-item unread">
                                    <div class="notification-icon">
                                        <i data-lucide="user"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-text">New user registration: John Doe</div>
                                        <div class="notification-time">2 minutes ago</div>
                                    </div>
                                    <button class="notification-close" onclick="removeNotification(this)">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                                <div class="notification-item unread">
                                    <div class="notification-icon">
                                        <i data-lucide="calendar"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-text">New booking confirmed for Sunset Villa</div>
                                        <div class="notification-time">15 minutes ago</div>
                                    </div>
                                    <button class="notification-close" onclick="removeNotification(this)">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                                <div class="notification-item read">
                                    <div class="notification-icon">
                                        <i data-lucide="message-circle"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-text">Customer inquiry about pricing</div>
                                        <div class="notification-time">1 hour ago</div>
                                    </div>
                                    <button class="notification-close" onclick="removeNotification(this)">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                                <div class="notification-item read">
                                    <div class="notification-icon">
                                        <i data-lucide="dollar-sign"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-text">Payment received for booking #1234</div>
                                        <div class="notification-time">2 hours ago</div>
                                    </div>
                                    <button class="notification-close" onclick="removeNotification(this)">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="notification-footer">
                                <a href="#" class="view-all">View all notifications</a>
                            </div>
                        </div>
                    </div>--}}
                    <div class="user-profile" onclick="toggleProfileDropdown()">
                        <div class="user-avatar">LM</div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name ?? '' }}</div>
                            <div class="user-email">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                        <i data-lucide="chevron-down" id="profile-chevron"></i>
                        <div class="profile-dropdown" id="profileDropdown">
                            {{--<a href="#" class="dropdown-item">
                                <i data-lucide="user"></i>
                                My Profile
                            </a>
                            <a href="#" class="dropdown-item">
                                <i data-lucide="settings"></i>
                                Settings
                            </a>--}}
                            <a href="{{ url('admin/logout') }}" class="dropdown-item">
                                <i data-lucide="log-out"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="dashboard-content">
                @include('layouts.admin.alert')

                @yield('content')
            </main>
        </div>
    </div>

    @include('admin.ajax.common_ajax')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>

</html>
