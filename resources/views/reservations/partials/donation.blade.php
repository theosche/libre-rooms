 <div class="form-group" id="donation-form-group">
    <h3 class="form-group-title">{{ __('Add a donation (optional)') }}</h3>
    <fieldset class="form-element">
        <div class="form-field">
            <label for="donation" class="form-element-title">{{ __('Donation in :currency', ['currency' => $currency]) }}</label>
            <input
                type="number"
                min="0"
                step=".01"
                id="donation"
                name="donation"
                value="{{ old('donation') ?? $reservationDonation }}"
            >
            @error('donation')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

    </fieldset>
 </div>
