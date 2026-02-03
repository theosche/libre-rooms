@extends('emails.layout')

@section('content')
    <h1>{{ __('Your reservation request') }}</h1>

    <p>{{ __('Hello') }},</p>

    <p>
        {{ __('We have received your reservation request for the room :room.', ['room' => $room->name]) }}
        {{ __('The reservation must be validated by an admin. You will then receive a confirmation email.') }}
    </p>

    <div class="highlight-box">
        <strong>{{ $reservation->title }}</strong>
        @if($reservation->description)
            <br><span style="color: #6b7280;">{{ $reservation->description }}</span>
        @endif
    </div>

    <h2>{{ $reservation->events->count() > 1 ? __('Pre-reserved dates') : __('Pre-reserved date') }}</h2>
    <ul>
        @foreach ($reservation->events as $event)
            <li>
                {{ $event->startLocalTz()->format('d.m.Y - H:i') }} {{ __('to') }} {{ $event->endLocalTz()->format('d.m.Y - H:i') }}
                <a href="{{ route('reservations.event-ics', ['hash' => $reservation->hash, 'uid' => $event->uid]) }}" style="font-size: 12px;">(ics)</a>
            </li>
        @endforeach
    </ul>

    <p>{{ __('For any questions, feel free to contact us by replying to this email.') }}</p>

    <p>{{ __('Best regards,') }}</p>
@endsection
