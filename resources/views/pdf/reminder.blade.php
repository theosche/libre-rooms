@extends('pdf.layouts.base')

@section('title', __('Reminder - Invoice :number', ['number' => $invoice->number]))

@section('content')
    @include('pdf.partials.header', ['owner' => $owner])
    <h1>{{ __('INVOICE') }} - {{ $invoice->formattedReminderCount() }}</h1>

    <div class="separator"></div>

    @php
        $ownerContact = $owner->contact;
        $invoiceDate = $invoice->issued_at;
        $dueDate = $invoice->due_at;
        $finalTotal = $reservation->finalPrice();
        $vatNumber = $owner->payment_instructions['vat_number'] ?? null;
    @endphp

    <div class="invoice-info">
        <div class="invoice-info-row">
            <div class="invoice-info-labels">
                <p>{{ __('Invoice no.') }}</p>
                <p>{{ __('Invoice date:') }}</p>
                <p>{{ __('Reminder date:') }}</p>
                <p>{{ __('New due date:') }}</p>
                <p>{{ __('Amount due:') }}</p>
                <p>{{ __('VAT number:') }}</p>
            </div>
            <div class="invoice-info-values">
                <p>{{ $invoice->number }}</p>
                <p>{{ $invoice->first_issued_at->format('d/m/Y') }}</p>
                <p>{{ $invoice->issued_at->format('d/m/Y') }}</p>
                <p>{{ $dueDate->format('d/m/Y') }}</p>
                <p>{{ currency($finalTotal, $owner) }}</p>
                <p>{{ $vatNumber ?? __('Not registered for VAT') }}</p>
            </div>
            <div class="invoice-info-tenant">
                @include('pdf.partials.tenant-address', ['tenant' => $tenant])
            </div>
        </div>
    </div>

    <div class="separator"></div>

    <div style="margin: 5mm 0;">
        <h2>{{ __('Reservation of :room', ['room' => $room->name]) }} - {{ html_entity_decode($reservation->title) }}</h2>
        @if($reservation->description)
            <p>{{ __('Event description:') }} {{ html_entity_decode($reservation->description) }}</p>
        @endif
    </div>

    @include('pdf.partials.events-table', ['reservation' => $reservation, 'room' => $room, 'owner' => $owner])

    @include('pdf.partials.totals', ['reservation' => $reservation, 'owner' => $owner])

    <div class="message">
        <p>
            {{ __('This reminder follows our invoice dated :date which remains unpaid to date.', ['date' => $invoice->first_issued_at->format('d/m/Y')]) }}
            {{ __('Please proceed with payment before :date.', ['date' => $dueDate->format('d/m/Y')]) }}
            {{ __('If you have already made the payment, please disregard this reminder.') }}
            {{ __('For any questions, feel free to contact us.') }}
        </p>
    </div>

    @if($paymentHtml)
        {!! $paymentHtml !!}
    @endif
@endsection
