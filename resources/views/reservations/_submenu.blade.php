@if($canViewAdmin)
    <nav class="page-submenu">
        <a href="{{ route('reservations.index', ['view' => 'mine']) }}"
           class="page-submenu-item page-submenu-nav {{ $view === 'mine' ? 'active' : '' }}">
            {{ __('My reservations') }}
        </a>
        <a href="{{ route('reservations.index', ['view' => 'admin']) }}"
           class="page-submenu-item page-submenu-nav {{ $view === 'admin' ? 'active' : '' }}">
            {{ __('Reservations to manage') }}
        </a>
    </nav>
@endif
