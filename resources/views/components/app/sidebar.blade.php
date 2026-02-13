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

   
        @foreach ($exams as $exam)
    @php
        $examSlug = Str::slug($exam->name);

        // can() method use karein, ye error nahi dega agar permission delete ho gayi ho
        $hasPermission = auth()->user()->can($examSlug) ||
                         auth()->user()->can($examSlug . '-access');
    @endphp

    @if ($hasPermission)
        <li class="menu-item {{ Request::is('tenant/questions/' . $exam->id . '*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-book-open"></i>
                <div class="text-capitalize">{{ $exam->name }}</div>
            </a>

            <ul class="menu-sub">
                @foreach ($exam->modules as $module)
                    <li class="menu-item {{ request()->is('tenant/questions/'.$exam->id.'/'.$module->id) ? 'active' : '' }}">
                        <a href="{{ route('tenant.questions.index', [$exam->id, $module->id]) }}" class="menu-link">
                            <div>{{ $module->name }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
@endforeach

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

             @can('manage-roles')
                <li class="menu-item {{ Request::is('users*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.form.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                        <div data-i18n="Roles">from create</div>
                    </a>
                </li>
            @endcan
             @can('manage-roles')
                <li class="menu-item {{ Request::is('users*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.form.builder') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                        <div data-i18n="Roles">from create</div>
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
