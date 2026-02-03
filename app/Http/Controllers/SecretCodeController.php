<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\View\View;

class SecretCodeController extends Controller
{
    /**
     * Display the secret codes for a confirmed reservation.
     * Public access via hash (obfuscated URL).
     */
    public function show(string $hash): View
    {
        $reservation = Reservation::where('hash', $hash)
            ->with(['room.owner', 'tenant', 'events'])
            ->firstOrFail();

        // Check if reservation is confirmed (only status that allows viewing codes)
        if ($reservation->status !== ReservationStatus::CONFIRMED) {
            abort(403, $this->getStatusMessage($reservation->status));
        }

        // Check if room has a secret message
        if (empty($reservation->room->secret_message)) {
            abort(404, __('No access codes available for this room.'));
        }

        return view('reservations.codes', [
            'reservation' => $reservation,
            'room' => $reservation->room,
        ]);
    }

    /**
     * Get a user-friendly message based on reservation status.
     */
    private function getStatusMessage(ReservationStatus $status): string
    {
        return match ($status) {
            ReservationStatus::PENDING => __('Access codes are only available once the reservation is confirmed.'),
            ReservationStatus::CANCELLED => __('This reservation has been cancelled.'),
            ReservationStatus::FINISHED => __('This reservation is finished. Access codes are no longer available.'),
        };
    }
}
