<ul class="nav-menu">
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/dashboard') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/dashboard') active @endif">
            <i data-lucide="layout-dashboard"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link" data-page="masterdata">
            <i data-lucide="database"></i>
            <span>Master Data</span>
            <i data-lucide="chevron-down" class="nav-chevron"></i>
        </a>
        <ul class="nav-submenu">
            <li>
                <a href="{{ url(getAdminRouteName() . '/countries') }}"
                    class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/countries') active @endif">
                    <i data-lucide="globe"></i>
                    <span>Countries</span>
                </a>
            </li>
            <li>
                <a href="{{ url(getAdminRouteName() . '/states') }}"
                    class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/states') active @endif">
                    <i data-lucide="map-pin"></i>
                    <span>States</span>
                </a>
            </li>
            <li>
                <a href="{{ url(getAdminRouteName() . '/cities') }}"
                    class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/cities') active @endif">
                    <i data-lucide="building"></i>
                    <span>Cities</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/properties') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/properties') active @endif">
            <i data-lucide="home"></i>
            <span>Properties</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/amenities') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/amenities') active @endif">
            <i data-lucide="list"></i>
            <span>Amenities</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/cms_pages') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/cms_pages') active @endif">
            <i data-lucide="file-edit"></i>
            <span>Pages & Section</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/blogs') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/blogs') active @endif">
            <i data-lucide="book"></i>
            <span>Blogs</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/testimonials') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/testimonials') active @endif">
            <i data-lucide="star"></i>
            <span>Success Stories</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link" data-page="gallery">
            <i data-lucide="image"></i>
            <span>Gallery</span>
            <i data-lucide="chevron-down" class="nav-chevron"></i>
        </a>
        <ul class="nav-submenu">
            <li>
                <a href="{{ url(getAdminRouteName() . '/gallery-categories') }}"
                    class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/gallery-categories') active @endif">
                    <i data-lucide="folder"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ url(getAdminRouteName() . '/gallery-images') }}"
                    class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/gallery-images') active @endif">
                    <i data-lucide="image"></i>
                    <span>Images</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/enquiries') }}" class="nav-link"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/enquiries') active @endif">
            <i data-lucide="message-square"></i>
            <span>Enquiries</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/email_templates') }}" class="nav-link"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/email_templates') active @endif">
            <i data-lucide="mail"></i>
            <span>Email Templates</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/website_settings') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/website_settings') active @endif">
            <i data-lucide="settings"></i>
            <span>Settings</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/activity_logs') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/activity_logs') active @endif">
            <i data-lucide="activity"></i>
            <span>Activity Logs</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/users') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/users') active @endif">
            <i data-lucide="users"></i>
            <span>Users</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/roles') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/roles') active @endif">
            <i data-lucide="user-check"></i>
            <span>Roles</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/users/customers') }}" class="nav-link">
            <i data-lucide="users"></i>
            <span>Customers</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ url(getAdminRouteName() . '/modules') }}"
            class="nav-link @if(getCurrentPageUrl() == '/' . getAdminRouteName() . '/modules') active @endif">
            <i data-lucide="grid"></i>
            <span>Modules</span>
        </a>
    </li>
</ul>
