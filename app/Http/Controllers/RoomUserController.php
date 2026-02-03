<?php

namespace App\Http\Controllers;

use App\Enums\RoomUserRoles;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomUserController extends Controller
{
    /**
     * Display the list of users with access to the room.
     */
    public function index(Room $room): View
    {
        $this->authorize('manageUsers', $room);

        $room->load(['users', 'owner.contact']);

        return view('rooms.users.index', [
            'room' => $room,
            'roles' => RoomUserRoles::cases(),
            'currentUser' => auth()->user(),
        ]);
    }

    /**
     * Add a user to the room.
     */
    public function store(Request $request, Room $room): RedirectResponse
    {
        $this->authorize('manageUsers', $room);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'string', 'in:' . implode(',', array_column(RoomUserRoles::cases(), 'value'))],
        ], [
            'email.exists' => __('No user found with this email address.'),
        ]);

        // Check if user can add this role
        $this->authorize('addRoomUser', [$room, $validated['role']]);

        $user = User::where('email', $validated['email'])->first();

        // Check if user already has access
        if ($room->users()->where('users.id', $user->id)->exists()) {
            return redirect()->route('rooms.users.index', $room)
                ->with('error', __('This user already has access to this room.'));
        }

        // Attach user to room
        $room->users()->attach($user->id, ['role' => $validated['role']]);

        return redirect()->route('rooms.users.index', $room)
            ->with('success', __('User added successfully.'));
    }

    /**
     * Remove a user from the room.
     */
    public function destroy(Room $room, User $user): RedirectResponse
    {
        $this->authorize('manageUsers', $room);

        // Check if user has access to this room
        if (! $room->users()->where('users.id', $user->id)->exists()) {
            return redirect()->route('rooms.users.index', $room)
                ->with('error', __('This user does not have direct access to this room.'));
        }

        // Detach user from room
        $room->users()->detach($user->id);

        return redirect()->route('rooms.users.index', $room)
            ->with('success', __('User removed successfully.'));
    }
}
