{{--
    Currency select field
    Props:
    - $name: input name
    - $id: input id
    - $value: current value (nullable)
    - $defaultCurrency: default currency to show in placeholder (optional)
    - $required: whether field is required (default: false)
    - $showDefaultOption: show "{{ __('Default settings') }}" option (default: true)
--}}

@php
    // Get localized currency names from ICU data
    $locale = app()->getLocale();
    $bundle = \ResourceBundle::create($locale, 'ICUDATA-curr');
    $icuCurrencies = $bundle?->get('Currencies');

    $currencyName = function (string $code) use ($icuCurrencies) {
        $data = $icuCurrencies?->get($code);
        $name = $data ? $data->get(1) : null;
        return $code . ' - ' . ($name ? ucfirst($name) : $code);
    };

    $currencies = [
        __('Europe') => [
            'CHF' => $currencyName('CHF'),
            'EUR' => $currencyName('EUR'),
            'GBP' => $currencyName('GBP'),
            'SEK' => $currencyName('SEK'),
            'NOK' => $currencyName('NOK'),
            'DKK' => $currencyName('DKK'),
            'PLN' => $currencyName('PLN'),
            'CZK' => $currencyName('CZK'),
            'HUF' => $currencyName('HUF'),
            'RON' => $currencyName('RON'),
            'BGN' => $currencyName('BGN'),
        ],
        __('Americas') => [
            'USD' => $currencyName('USD'),
            'CAD' => $currencyName('CAD'),
            'MXN' => $currencyName('MXN'),
            'BRL' => $currencyName('BRL'),
            'ARS' => $currencyName('ARS'),
        ],
        __('Asia & Pacific') => [
            'JPY' => $currencyName('JPY'),
            'CNY' => $currencyName('CNY'),
            'HKD' => $currencyName('HKD'),
            'SGD' => $currencyName('SGD'),
            'AUD' => $currencyName('AUD'),
            'NZD' => $currencyName('NZD'),
            'INR' => $currencyName('INR'),
            'KRW' => $currencyName('KRW'),
        ],
        __('Africa & Middle East') => [
            'ZAR' => $currencyName('ZAR'),
            'AED' => $currencyName('AED'),
            'ILS' => $currencyName('ILS'),
        ],
    ];

    $showDefaultOption = $showDefaultOption ?? true;
    $required = $required ?? false;
@endphp

<select name="{{ $name }}" id="{{ $id }}" @if($required) required @endif>
    @if($showDefaultOption)
        <option value="">
            {{ __('Default settings') }}
            @if(isset($defaultCurrency))
                ({{ $defaultCurrency }})
            @endif
        </option>
    @endif

    @foreach($currencies as $region => $regionCurrencies)
        <optgroup label="{{ $region }}">
            @foreach($regionCurrencies as $code => $label)
                <option value="{{ $code }}" @selected(old($name, $value) == $code)>
                    {{ $label }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
