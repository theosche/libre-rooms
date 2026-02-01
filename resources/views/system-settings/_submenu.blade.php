<nav class="page-submenu">
    <a href="{{ route('system-settings.edit') }}"
       class="page-submenu-item page-submenu-nav {{ request()->routeIs('system-settings.edit') ? 'active' : '' }}">
        Réglages généraux
    </a>
    <a href="{{ route('identity-providers.index') }}"
       class="page-submenu-item page-submenu-nav {{ request()->routeIs('identity-providers.*') ? 'active' : '' }}">
        Fournisseurs d'identité
    </a>
    <a href="{{ route('setup.environment') }}"
       class="page-submenu-item page-submenu-nav {{ request()->routeIs('setup.environment') ? 'active' : '' }}">
        Environnement (.env)
    </a>
</nav>
