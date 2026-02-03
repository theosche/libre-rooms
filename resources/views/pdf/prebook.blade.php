@extends('pdf.layouts.base')

@section('title', __('Pre-booking confirmation'))

@section('content')
    @include('pdf.partials.header', ['owner' => $owner])

    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%;"></div>
        <div style="display: table-cell; width: 50%;">
            @include('pdf.partials.tenant-address', ['tenant' => $tenant])
        </div>
    </div>

    <h1 style="text-align: center; margin: 10mm 0;">{{ __('Pre-booking confirmation for :room', ['room' => $room->name]) }}</h1>

    @php
        $ownerContact = $owner->contact;
        $currentDate = now();
    @endphp

    <p>{{ $ownerContact->city }}, {{ $currentDate->translatedFormat('j F Y') }}</p>

    <div style="margin: 8mm 0;">
        <h2>{{ html_entity_decode($reservation->title) }}</h2>
        @if($reservation->description)
            <p>{{ html_entity_decode($reservation->description) }}</p>
        @endif
    </div>

    @include('pdf.partials.events-table', ['reservation' => $reservation, 'room' => $room, 'owner' => $owner])

    @include('pdf.partials.totals', ['reservation' => $reservation, 'owner' => $owner])

    <div class="message">
        <p>
            {{ __('Your reservation request for :room has been received.', ['room' => $room->name]) }}
            {{ __('The reservation must be confirmed by :owner before it becomes effective.', ['owner' => lcfirst($ownerContact->display_name())]) }}
            {{ __('You will receive a confirmation and an invoice by email.') }}
            {{ __('If the deadlines are short and you do not receive anything, please contact us!') }}
        </p>
    </div>
@endsection
