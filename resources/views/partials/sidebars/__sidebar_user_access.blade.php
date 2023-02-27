@if (hasModulePermission('User Access', $active_modules)
&& hasAnyPermission(['permission.accesses.create', 'permission.permitted.users', 'database.backup', 'attendance.devices.index', 'device.api'], $slugs))
    <li class="{{ request()->segment(1) == "setting" || request()->segment(1) == 'machine-integration' ? 'open' : '' }}">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-key" style=" transform: rotate(45deg); color:gold; font-weight:bolder"></i>
            <span class="menu-text">User Access</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>

        <ul class="submenu">
            @if (hasPermission("permission.accesses.create", $slugs))
                <li class="{{ request()->is('setting/permission-access/create') ? 'active' : '' }}">
                    <a href="{{ route('permission-access.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Permission Access
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission("permission.permitted.users", $slugs))
                <li class="{{ request()->is('setting/views-permitted-users') ? 'active' : '' }}">
                    <a href="{{ route('permitted.users') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Permitted Users
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="{{ request()->is('setting/permission-access-employee') ? 'active' : '' }}">
                    <a href="{{ route('permission-access.employee') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Employee Permission
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            {{-- @if (auth()->id() == 1)
                <li class="">
                    <a href="https://api-inovace360.com/clients" target="_blank">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Device Api
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif --}}

            @if (hasPermission("database.backup", $slugs))
                <li class="">
                    <a href="{{ route('db-backup') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Backup Database
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a href="{{ route('db-backup-to-drive') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Backup To Drive
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            <!--machine-integration -->
            @if(hasModulePermission('HRM', $active_modules) && hasPermission('attendance.devices.index', $slugs) && file_exists(base_path() . '/module/HRM/routes/web_hrm.php'))
                <li class="{{ request()->segment(1) == "machine-integration" ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-magic"></i>
                        <span class="menu-text">Integration</span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        <li class="{{ request()->is('machine-integration/attendance-device') ? 'active' : '' }}">
                            <a href="{{ route('attendance-device.index') }}">
                                <i class="menu-icon fa fa-thumbs-o-up"></i>
                                Machine Device
                            </a>
                            <b class="arrow"></b>
                        </li>


                        <li class="{{ request()->is('hrm/devices') ? 'active' : '' }}">
                            <a href="{{ route('devices.index') }}">
                                <i class="menu-icon fa fa-hdd-o"></i>
                                Attnd. Device
                            </a>
                            <b class="arrow"></b>
                        </li>


                        <li class="{{ request()->is('hrm/attendance-logs') ? 'active' : '' }}">
                            <a href="{{ route('attendance-logs.index') }}">
                                <i class="menu-icon fa fa-hdd-o"></i>
                                Attnd. Logs
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
            @endif



            @if (auth()->id() == 1)
                <li class="">
                    <a href="/system-setting">
                        <i class="menu-icon fa fa-caret-right"></i>
                        System Setting
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif
        </ul>
    </li>
@endif
