<nav class="page-submenu">
    <a href="{{ route('system-settings.edit') }}"
       class="page-submenu-item page-submenu-nav {{ request()->routeIs('system-settings.edit') ? 'active' : '' }}">
        {{ __('General settings') }}
    </a>
    <a href="{{ route('identity-providers.index') }}"
       class="page-submenu-item page-submenu-nav {{ request()->routeIs('identity-providers.*') ? 'active' : '' }}">
        {{ __('Identity providers') }}
    </a>
    <a href="{{ route('setup.environment') }}"
       class="page-submenu-item page-submenu-nav {{ request()->routeIs('setup.environment') ? 'active' : '' }}">
        {{ __('Environment (.env)') }}
    </a>
</nav>
