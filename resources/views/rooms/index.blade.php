@extends('layouts.app')

@section('title', __('Available rooms'))

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="page-header">
        <h1 class="page-header-title">{{ __('Rooms') }}</h1>

        @include('rooms._submenu', ['view' => $view])

        @cannot('viewMine', App\Models\Room::class)
            <p class="mt-2 text-sm text-gray-600">{{ __('List of all rooms available for reservation') }}</p>
        @endcannot
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('rooms.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="view" value="{{ $view }}">
            <div>
                <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Owner') }}</label>
                <select name="owner_id" id="owner_id" class="form-select">
                    <option value="">{{ __('All owners') }}</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                            {{ $owner->contact->display_name() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 col-span-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('Filter') }}
                </button>
                @if(request()->has('owner_id'))
                    <a href="{{ route('rooms.index', ['view' => $view]) }}" class="btn btn-secondary">
                        {{ __('Reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau des salles -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Owner') }}
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-on-mobile">
                        {{ __('Description') }}
                    </th>
                    @if($view === 'mine')
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                    @endif
                    @if($user?->canManageAnyOwner())
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 cursor-pointer transition" onclick="toggleDetails({{ $room->id }})">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            <a href="{{ route('rooms.show', $room) }}" onclick="event.stopPropagation()" class="room-name-link">
                                {{ $room->name }}
                                <svg class="room-name-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if($user?->isAdminOf($room->owner))
                                <a href="{{ route('owners.edit', $room->owner) }}" onclick="event.stopPropagation()">
                                    {{ $room->owner->contact->display_name() }}
                                </a>
                            @else
                                {{ $room->owner->contact->display_name() }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 hide-on-mobile">
                            {{ Str::limit($room->description, 100) }}
                        </td>
                        @if($view === 'mine')
                            <td class="px-4 py-3">
                                <div class="flex gap-1 flex-wrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $room->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $room->active ? __('Active') : __('Inactive') }}
                                    </span>
                                    @if(!$room->is_public)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ __('Private') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        @endif
                        @if(auth()->user()?->canManageAnyOwner())
                            <td class="px-4 py-3 text-sm font-medium">
                                <div class="action-group" onclick="event.stopPropagation()">
                                    @can('manageUsers', $room)
                                        <a href="{{ route('rooms.users.index', $room) }}" class="link-primary">
                                            {{ __('Users') }}
                                        </a>
                                    @endcan

                                    @can('update', $room)
                                        <a href="{{ route('rooms.edit', $room) }}" class="link-primary">
                                            {{ __('Edit') }}
                                        </a>

                                        <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this room? This action cannot be undone.') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="link-danger">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        @endif
                    </tr>

                    <!-- Détails dépliables -->
                    @php
                        $colspan = 3; // Nom, Propriétaire, Description
                        if ($view === 'mine') $colspan++; // Statut
                        if (auth()->user()?->canManageAnyOwner()) $colspan++; // Actions
                    @endphp
                    <tr id="details-{{ $room->id }}" class="details-row hidden">
                        <td colspan="{{ $colspan }}" class="px-4 py-3 bg-slate-50 border-t border-slate-200 w-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                                <!-- Adresse -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Address') }}</h4>
                                    @if($room->hasAddress())
                                        <p class="text-sm text-slate-700">{{ $room->formattedAddress() }}</p>
                                    @else
                                        <p class="text-sm text-slate-400">{{ __('Address not provided') }}</p>
                                    @endif
                                </div>

                                <!-- Charte -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Charter') }}</h4>
                                    @if($room->charter_mode->value === 'text')
                                        <p class="text-sm text-slate-700 line-clamp-4">{{ $room->charter_str }}</p>
                                    @elseif($room->charter_mode->value === 'link')
                                        <a href="{{ $room->charter_str }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 rounded-md text-sm text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ __('View charter') }}
                                        </a>
                                    @else
                                        <p class="text-sm text-slate-400">{{ __('No charter') }}</p>
                                    @endif
                                </div>

                                <!-- Tarifs -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Prices') }}</h4>
                                    @if($room->price_mode->value === 'fixed')
                                        <div class="bg-white rounded-lg border border-slate-200 p-3 space-y-2">
                                            @if($room->price_short && $room->max_hours_short)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-slate-500">{{ $room->shortPriceRuleLabel() }}</span>
                                                    <span class="text-slate-900 font-medium">{{ currency($room->price_short, $room->owner) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between text-sm">
                                                <span class="text-slate-500">{{ __('Full day') }}</span>
                                                <span class="text-slate-900 font-medium">{{ currency($room->price_full_day, $room->owner) }}</span>
                                            </div>
                                        </div>
                                    @elseif($room->price_mode->value === 'free')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">{{ __('Pay what you want') }}</span>
                                    @else
                                        <p class="text-sm text-slate-400">{{ __('Not specified') }}</p>
                                    @endif

                                    @if($room->discounts->where('active', true)->count() > 0)
                                        <div class="mt-3 space-y-1">
                                            @foreach($room->discounts->where('active', true) as $discount)
                                                @if($user?->isAdminOf($room->owner))
                                                    <a href="{{ route('room-discounts.edit', $discount) }}">
                                                @endif
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-green-600 font-medium">
                                                        @if($discount->type->value === 'fixed')
                                                            {{ currency(-$discount->value, $room->owner) }}
                                                        @else
                                                            -{{ $discount->value }}%
                                                        @endif
                                                    </span>
                                                    <span class="text-slate-600">{{ $discount->name }}</span>
                                                    @if($discount->limit_to_contact_type)
                                                        <span class="text-slate-400 text-xs">({{ $discount->limit_to_contact_type->value === 'individual' ? __('Private') : __('Org.') }})</span>
                                                    @endif
                                                </div>
                                                @if($user?->isAdminOf($room->owner))
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Règles -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Rules') }}</h4>
                                    <dl class="space-y-2 text-sm">
                                        @if($room->reservation_cutoff_days)
                                            <div class="flex">
                                                <dt class="text-slate-500 mr-4">{{ __('Min. delay') }}</dt>
                                                <dd class="text-slate-900">{{ __(':days d before', ['days' => $room->reservation_cutoff_days]) }}</dd>
                                            </div>
                                        @endif
                                        @if($room->reservation_advance_limit)
                                            <div class="flex">
                                                <dt class="text-slate-500 mr-4">{{ __('Max. advance') }}</dt>
                                                <dd class="text-slate-900">{{ __(':days d in advance', ['days' => $room->reservation_advance_limit]) }}</dd>
                                            </div>
                                        @endif
                                        @if(!$room->reservation_cutoff_days && !$room->reservation_advance_limit)
                                            <p class="text-slate-400">{{ __('No restrictions') }}</p>
                                        @endif
                                    </dl>
                                </div>

                                <!-- Options -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Options') }}</h4>
                                    @if($room->options->where('active', true)->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($room->options->where('active', true) as $option)
                                                @if($user?->isAdminOf($room->owner))
                                                    <a href="{{ route('room-options.edit', $option) }}">
                                                @endif
                                                <div class="bg-white rounded-lg border border-slate-200 p-2">
                                                    <div class="flex justify-between items-start">
                                                        <span class="text-sm text-slate-700">{{ $option->name }}</span>
                                                        <span class="text-sm text-slate-900 font-medium shrink-0 ml-2">{{ currency($option->price, $room->owner) }}</span>
                                                    </div>
                                                    @if($option->description)
                                                        <p class="text-xs text-slate-500 mt-1">{{ $option->description }}</p>
                                                    @endif
                                                </div>
                                                @if($user?->isAdminOf($room->owner))
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400">{{ __('No options') }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    @php
                        $emptyColspan = 3;
                        if ($view === 'mine') $emptyColspan++;
                        if ($user?->canManageAnyOwner()) $emptyColspan++;
                    @endphp
                    <tr>
                        <td colspan="{{ $emptyColspan }}" class="px-4 py-3 text-center text-gray-500">
                            {{ __('No room found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
</div>

<script>
    function toggleDetails(roomId) {
        const detailsRow = document.getElementById(`details-${roomId}`);
        if (detailsRow.classList.contains('hidden')) {
            // Fermer tous les autres détails
            document.querySelectorAll('[id^="details-"]').forEach(row => {
                row.classList.add('hidden');
            });
            // Ouvrir celui-ci
            detailsRow.classList.remove('hidden');
        } else {
            detailsRow.classList.add('hidden');
        }
    }
</script>
@endsection
