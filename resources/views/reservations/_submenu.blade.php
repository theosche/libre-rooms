@if($canViewAdmin)
    <nav class="page-submenu">
        <a href="{{ route('reservations.index', ['view' => 'mine']) }}"
           class="page-submenu-item page-submenu-nav {{ $view === 'mine' ? 'active' : '' }}">
            Mes réservations
        </a>
        <a href="{{ route('reservations.index', ['view' => 'admin']) }}"
           class="page-submenu-item page-submenu-nav {{ $view === 'admin' ? 'active' : '' }}">
            Réservations à gérer
        </a>
    </nav>
@endif
