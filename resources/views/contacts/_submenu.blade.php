@if($user->is_global_admin)
    <nav class="page-submenu">
        <a href="{{ route('contacts.index', ['view' => 'mine']) }}"
           class="page-submenu-item page-submenu-nav {{ ($view ?? 'mine') === 'mine' ? 'active' : '' }}">
            Mes contacts
        </a>
        <a href="{{ route('contacts.index', ['view' => 'all']) }}"
           class="page-submenu-item page-submenu-nav {{ ($view ?? '') === 'all' ? 'active' : '' }}">
            Tous les contacts
        </a>

        <span class="page-submenu-separator"></span>

        <a href="{{ route('contacts.create') }}" class="page-submenu-item page-submenu-action">
            + Nouveau contact
        </a>
    </nav>
@else
    <nav class="page-submenu">
        <a href="{{ route('contacts.create') }}" class="page-submenu-item page-submenu-action">
            + Nouveau contact
        </a>
    </nav>
@endif
