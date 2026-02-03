 <div class="form-group" id="free-price-form-group">
    <h3 class="form-group-title">{{ __('Free pricing') }}</h3>
    <fieldset class="form-element">
        <div class="form-field">
            <label for="free-price" class="form-element-title">{{ __('Set your own price for the reservation in :currency', ['currency' => $currency]) }} *</label>
            <input
                type="number"
                min="0"
                step=".01"
                id="free-price"
                name="donation"
                required
                value="{{ old('donation') ?? $freePrice }}"
            >
            @error('donation')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

    </fieldset>
 </div>
