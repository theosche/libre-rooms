<?php

namespace App\Http\Controllers;

use App\Enums\OwnerUserRoles;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OwnerUserController extends Controller
{
    /**
     * Display the list of users with access to the owner.
     */
    public function index(Owner $owner): View
    {
        $this->authorize('manageUsers', $owner);

        $owner->load(['users', 'contact']);
        $currentUser = auth()->user();

        return view('owners.users.index', [
            'owner' => $owner,
            'roles' => OwnerUserRoles::cases(),
            'isAdmin' => $currentUser->isAdminOf($owner),
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Add a user to the owner or update their role.
     */
    public function store(Request $request, Owner $owner): RedirectResponse
    {
        $this->authorize('manageUsers', $owner);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'string', 'in:' . implode(',', array_column(OwnerUserRoles::cases(), 'value'))],
        ], [
            'email.exists' => __('No user found with this email address.'),
        ]);

        // Check if user can add this role
        $this->authorize('addOwnerUser', [$owner, $validated['role']]);

        $user = User::where('email', $validated['email'])->first();
        $currentUser = auth()->user();

        // Check if it's the current user
        if ($user->id === $currentUser->id) {
            return redirect()->route('owners.users.index', $owner)
                ->with('error', __('You cannot modify your own role.'));
        }

        // Check if user already has access
        $existingRole = $user->getRoleForOwner($owner);
        if ($existingRole !== null) {
            // User exists - update role if allowed
            $newRole = OwnerUserRoles::from($validated['role']);

            // Moderators can't overwrite a higher role with viewer
            if (! $currentUser->isAdminOf($owner)) {
                if ($existingRole->hasAtLeast(OwnerUserRoles::MODERATOR) && $newRole === OwnerUserRoles::VIEWER) {
                    return redirect()->route('owners.users.index', $owner)
                        ->with('error', __('You cannot demote a moderator or admin to viewer.'));
                }
            }

            // Update the role
            $user->owners()->updateExistingPivot($owner->id, ['role' => $validated['role']]);

            return redirect()->route('owners.users.index', $owner)
                ->with('success', __('User role updated.'));
        }

        // Attach user to owner
        $user->owners()->attach($owner->id, ['role' => $validated['role']]);

        return redirect()->route('owners.users.index', $owner)
            ->with('success', __('User added successfully.'));
    }

    /**
     * Remove a user from the owner.
     */
    public function destroy(Owner $owner, User $user): RedirectResponse
    {
        $this->authorize('manageUsers', $owner);

        // Check if user has access to this owner
        if (! $owner->users()->where('users.id', $user->id)->exists()) {
            return redirect()->route('owners.users.index', $owner)
                ->with('error', __('This user does not have access to this owner.'));
        }

        // Check if can remove this specific user
        $this->authorize('removeOwnerUser', [$owner, $user]);

        // Detach user from owner
        $owner->users()->detach($user->id);

        return redirect()->route('owners.users.index', $owner)
            ->with('success', __('User removed successfully.'));
    }
}
