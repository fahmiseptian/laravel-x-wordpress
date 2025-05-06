<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="#" class="text-nowrap logo-img align-items-center mx-auto">
                <img src="{{ asset('assets/images/logos/logo.png') }}" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="{{ route('home') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">COMPONENTS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('posts') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Posts</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('page') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="material-symbols:page-header-sharp" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Pages</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('contact-us') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="ic:baseline-message" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Messages</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('banner') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="material-symbols:planner-banner-ad-pt-rounded" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Banner Settings</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('theme') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="mdi:theme" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Theme Builder</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('users') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="mdi:users" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Users</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>