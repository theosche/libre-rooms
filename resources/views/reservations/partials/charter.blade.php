@if($charter_mode != App\Enums\CharterModes::NONE)
    <div class="form-group" id="donation-form-group">
        <h3 class="form-group-title">{{ __('Charter') }}</h3>
        <div class="form-element">
            @if($charter_mode == App\Enums\CharterModes::TEXT)
                <div class="form-field">
                    <p id="charter-text">{{ $charter_str }}</p>
                </div>
            @endif
                <div class="form-field">
                    <input
                        type="checkbox"
                        id="charter_checkbox"
                        name="charter"
                        value="1"
                        required
                        @checked(!$isCreate || old('charter'))
                    >
                    <label for="charter_checkbox">
                        @if($charter_mode == App\Enums\CharterModes::TEXT)
                            {{ __('I have read the charter and agree to it.') }}
                        @elseif($charter_mode == App\Enums\CharterModes::LINK)
                            {!! __('I have read <a href=":url">the charter</a> and agree to it.', ['url' => $charter_str]) !!}
                        @endif
                    </label>
                    @error('charter')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
        </div>
    </div>
@endif
