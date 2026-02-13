<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('tenant.dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('tenant.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        {{-- Agar user ke paas niche di gayi kisi bhi ek permission ka access hai --}}
        @canany(['create-course', 'edit-course', 'delete-course'])
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">CELPIP Questions</span>
            </li>

            <li class="menu-item {{ Request::is('celpip*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Celpip Add question</div>
                </a>
                <ul class="menu-sub">
                    {{-- Inhe aap alag-alag permissions se bhi control kar sakte hain --}}
                    <li class="menu-item">
                        <a href="{{ route('tenant.celpip.listening.add') }}" class="menu-link">
                            <div data-i18n="listening">Listening</div>
                        </a>
                    </li>
                    {{--
                    <li class="menu-item">
                        <a href="{{ route('app.celpip.reading.add.question') }}" class="menu-link">
                            <div data-i18n="Reading">Reading</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('app.celpip.speaking.add.question') }}" class="menu-link">
                            <div data-i18n="Speaking">Speaking</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('app.celpip.writing.add.question') }}" class="menu-link">
                            <div data-i18n="Writing">Writing</div>
                        </a>
                    </li> --}}
                </ul>
            </li>
        @endcanany

        @canany(['manage-users', 'manage-roles'])
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Administration</span>
            </li>

            @can('manage-users')
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user"></i>
                        <div data-i18n="Users">Manage Users</div>
                    </a>
                </li>
            @endcan

            @can('manage-roles')
                <li class="menu-item {{ Request::is('users*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.users.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                        <div data-i18n="Roles">Roles & Permissions</div>
                    </a>
                </li>
            @endcan
            @can('manage-roles')
                <li class="menu-item {{ Request::is('users*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.role.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                        <div data-i18n="Roles">Roles Create</div>
                    </a>
                </li>
            @endcan
        @endcanany

        @can('view-reports')
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-chart"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
