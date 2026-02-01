@if($canViewAdmin)
    <nav class="page-submenu">
        <a href="{{ route('invoices.index', ['view' => 'mine']) }}"
           class="page-submenu-item page-submenu-nav {{ $view === 'mine' ? 'active' : '' }}">
            Mes factures
        </a>
        <a href="{{ route('invoices.index', ['view' => 'admin']) }}"
           class="page-submenu-item page-submenu-nav {{ $view === 'admin' ? 'active' : '' }}">
            Factures à gérer
        </a>
    </nav>
@endif
