@extends('emails.layout')

@section('content')
    <h1>{{ __('Late invoices') }}</h1>

    <p>{{ __('Hello') }},</p>

    <div class="warning-box">
        <strong>{{ __(':count late invoice', ['count' => $lateCount]) }}{{ $lateCount > 1 ? 's' : '' }}</strong>
        {{ $lateCount > 1 ? __('require your attention.') : __('requires your attention.') }}
    </div>

    <p>
        {{ __('We remind you that some invoices are awaiting payment and have passed their due date.') }}
    </p>

    <p>
        {{ __('Please check if these invoices have been paid and mark them as paid, or send a payment reminder if necessary.') }}
    </p>

    <p>
        <a href="{{ route('invoices.index', ['view' => 'admin', 'status' => 'late']) }}" class="btn">
            {{ __('View late invoices') }}
        </a>
    </p>

    <p>{{ __('Best regards,') }}</p>
@endsection
