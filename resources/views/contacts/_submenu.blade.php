@if($user->is_global_admin)
    <div class="mt-4 flex gap-2 flex-wrap">
        <a href="{{ route('contacts.index', ['view' => 'mine']) }}"
           class="px-4 py-2 rounded-md {{ ($view ?? 'mine') === 'mine' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Mes contacts
        </a>
        <a href="{{ route('contacts.index', ['view' => 'all']) }}"
           class="px-4 py-2 rounded-md {{ ($view ?? '') === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Tous les contacts
        </a>
    </div>
@endif
