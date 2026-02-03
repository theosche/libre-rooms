<div class="payment-section payment-section-international">
    <div class="payment-info">
        <h3>{{ __('Payment instructions') }}</h3>

        <p><span class="label">{{ __('Beneficiary:') }}</span> {{ $instructions['account_holder'] }}</p>
        <p><span class="label">{{ __('IBAN:') }}</span> {{ $instructions['iban'] }}</p>
        @if(!empty($instructions['bic']))
            <p><span class="label">{{ __('BIC/SWIFT:') }}</span> {{ $instructions['bic'] }}</p>
        @endif
        @if(!empty($instructions['bank_name']))
            <p><span class="label">{{ __('Bank:') }}</span> {{ $instructions['bank_name'] }}</p>
        @endif
        @if(!empty($instructions['address']))
            <p><span class="label">{{ __('Address') }}:</span>
                {{ $instructions['address']['street'] ?? '' }},
                {{ $instructions['address']['zip'] ?? '' }} {{ $instructions['address']['city'] ?? '' }}
                @if(!empty($instructions['address']['country']))
                    ({{ $instructions['address']['country'] }})
                @endif
            </p>
        @endif

        <div style="margin-top: 3mm; border-top: 1px solid #ddd; padding-top: 3mm;">
            <p><span class="label">{{ __('Amount:') }}</span> <strong>{{ currency($invoice->amount, $invoice->owner) }}</strong></p>
            <p><span class="label">{{ __('Reference:') }}</span> {{ __('Invoice') }} {{ $invoice->number }}</p>
        </div>

        @if(!empty($instructions['vat_number']))
            <p style="margin-top: 3mm;"><span class="label">{{ __('VAT no.:') }}</span> {{ $instructions['vat_number'] }}</p>
        @endif
    </div>
</div>
