@extends('layouts.app')

@section('title', 'Propriétaires')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="page-header">
        <h1 class="page-header-title">Propriétaires</h1>
        <p class="mt-2 text-sm text-gray-600">Liste des propriétaires de salles</p>
        @can('create', App\Models\Owner::class)
            <nav class="page-submenu">
                <a href="{{ route('owners.create') }}" class="page-submenu-item page-submenu-action">
                    + Nouveau propriétaire
                </a>
            </nav>
        @endcan
    </div>

    <!-- Tableau des propriétaires -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nom
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salles
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Admins et Modérateur·ice·s
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($owners as $owner)
                    @php
                        // Filter to show only admins and moderators (not viewers)
                        $adminsAndModerators = $owner->users->filter(function ($u) {
                            $role = \App\Enums\OwnerUserRoles::tryFrom($u->pivot->role);
                            return $role && $role->hasAtLeast(\App\Enums\OwnerUserRoles::MODERATOR);
                        });
                        $otherAdminsAndMods = $adminsAndModerators->where('id', '!=', $user->id);
                    @endphp
                    <tr class="hover:bg-gray-50 cursor-pointer transition" onclick="toggleDetails({{ $owner->id }})">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            <div class="contact-info">
                                <span class="contact-info-name">{{ $owner->contact->display_name() }}</span>
                                <div class="contact-info-icons" onclick="event.stopPropagation()">
                                    @if($owner->contact->phone)
                                        <a href="tel:{{ $owner->contact->phone }}" class="text-blue-600 hover:text-blue-800" title="{{ $owner->contact->phone }}">
                                            <x-icons.phone />
                                        </a>
                                    @endif
                                    <a href="mailto:{{ $owner->contact->email }}" class="text-blue-600 hover:text-blue-800" title="{{ $owner->contact->email }}">
                                        <i class="fa-regular fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @if($owner->rooms->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($owner->rooms as $room)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                            {{ $room->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">Aucune salle</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @if($otherAdminsAndMods->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($otherAdminsAndMods as $adminMod)
                                        @php
                                            $role = \App\Enums\OwnerUserRoles::tryFrom($adminMod->pivot->role);
                                            $badgeColor = match($role) {
                                                \App\Enums\OwnerUserRoles::ADMIN => 'bg-red-100 text-red-700',
                                                \App\Enums\OwnerUserRoles::MODERATOR => 'bg-yellow-100 text-yellow-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <div>
                                        <span class="px-2 py-1 {{ $badgeColor }} text-xs rounded">
                                            {{ $adminMod->name }}
                                        </span>
                                        {!! $role === \App\Enums\OwnerUserRoles::ADMIN ? '<i class="fas fa-user-lock text-sm mt-1 ml-1 mr-2"></i>' : '' !!}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">Vous seul·e</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-medium" onclick="event.stopPropagation()">
                            <div class="action-group">
                                @can('manageUsers', $owner)
                                    <a href="{{ route('owners.users.index', $owner) }}" class="link-primary">
                                        Utilisateurs
                                    </a>
                                @endcan
                                @can('update', $owner)
                                    <a href="{{ route('owners.edit', $owner) }}" class="link-primary">
                                        Modifier
                                    </a>
                                @endcan
                                @can('delete', $owner)
                                    <form method="POST" action="{{ route('owners.destroy', $owner) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        @php
                                            $otherUsers = $owner->users->where('id', '!=', $user->id);
                                        @endphp
                                        @if($otherUsers->count() > 0 && !$user->is_global_admin)
                                            <button type="submit" class="link-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce propriétaire de votre liste ? D\'autres utilisateurs y ont également accès, il ne sera pas supprimé définitivement.')">
                                                Retirer
                                            </button>
                                        @else
                                            <button type="submit" class="link-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce propriétaire ? Cette action est irréversible et supprimera également toutes les salles associées.')">
                                                Supprimer
                                            </button>
                                        @endif
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Détails dépliables -->
                    <tr id="details-{{ $owner->id }}" class="details-row hidden">
                        <td colspan="4" class="px-4 py-3 bg-slate-50 border-t border-slate-200 w-0">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                <!-- Facturation -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Facturation</h4>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Slug</dt>
                                            <dd class="text-slate-900">{{ $owner->slug }}</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Échéance</dt>
                                            <dd class="text-slate-900">{{ $owner->invoice_due_mode->shortLabel($owner->invoice_due_days) }}</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Après rappel</dt>
                                            <dd class="text-slate-900">{{ $owner->invoice_due_days_after_reminder }}j</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Max rappels</dt>
                                            <dd class="text-slate-900">{{ $owner->max_nb_reminders }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Paramètres régionaux -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Régional</h4>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Fuseau</dt>
                                            <dd class="text-slate-900">{{ $owner->timezone ?: 'Défaut' }}</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Devise</dt>
                                            <dd class="text-slate-900">{{ $owner->currency ?: 'Défaut' }}</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="text-slate-500 mr-4">Langue</dt>
                                            <dd class="text-slate-900">{{ $owner->locale ?: 'Défaut' }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Intégrations -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Intégrations</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-sm">
                                            @if($owner->use_caldav)
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                <span class="text-slate-700">CalDAV activé</span>
                                            @else
                                                <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                                                <span class="text-slate-400">CalDAV désactivé</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-sm">
                                            @if($owner->usesWebdav())
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                <span class="text-slate-700">WebDAV activé</span>
                                            @else
                                                <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                                                <span class="text-slate-400">WebDAV désactivé</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Paiement -->
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Instructions de paiement</h4>
                                    @php
                                        $paymentInstructions = $owner->payment_instructions;
                                        $paymentType = $paymentInstructions['type'] ?? null;
                                    @endphp
                                    @if($paymentType)
                                        <div class="bg-white rounded-lg border border-slate-200 p-3">
                                            <div class="flex items-center gap-2 mb-2">
                                                @if($paymentType === 'swiss')
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded">QR Suisse</span>
                                                @elseif($paymentType === 'sepa')
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded">SEPA</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded">International</span>
                                                @endif
                                            </div>
                                            <dl class="space-y-1 text-xs">
                                                <div>
                                                    <dt class="text-slate-400">Titulaire</dt>
                                                    <dd class="text-slate-700 truncate">{{ $paymentInstructions['account_holder'] ?? '—' }}</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-400">IBAN</dt>
                                                    <dd class="text-slate-700 font-mono truncate">{{ $paymentInstructions['iban'] ?? '—' }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400">Non configuré</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                            Aucun propriétaire trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $owners->links() }}
    </div>
</div>

<script>
    function toggleDetails(ownerId) {
        const detailsRow = document.getElementById(`details-${ownerId}`);
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
