// Geocoding functionality using Nominatim API
const t = window.translations || {};

document.addEventListener('DOMContentLoaded', () => {
    const geocodeButton = document.getElementById('geocode-button');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const streetInput = document.getElementById('street');
    const postalCodeInput = document.getElementById('postal_code');
    const cityInput = document.getElementById('city');
    const countryInput = document.getElementById('country');
    const geocodeError = document.getElementById('geocode-error');
    const geocodeLoading = document.getElementById('geocode-loading');

    if (!geocodeButton || !latitudeInput || !longitudeInput) {
        return;
    }

    geocodeButton.addEventListener('click', async () => {
        // Build the query from address fields
        const parts = [];

        if (streetInput && streetInput.value.trim()) {
            parts.push(streetInput.value.trim());
        }
        if (postalCodeInput && postalCodeInput.value.trim()) {
            parts.push(postalCodeInput.value.trim());
        }
        if (cityInput && cityInput.value.trim()) {
            parts.push(cityInput.value.trim());
        }
        if (countryInput && countryInput.value.trim()) {
            parts.push(countryInput.value.trim());
        }

        if (parts.length < 2) {
            showError(t.geocode_fill_fields || 'Please fill in at least the street and city.');
            return;
        }

        const query = parts.join(', ');

        // Show loading state
        geocodeButton.disabled = true;
        if (geocodeLoading) {
            geocodeLoading.classList.remove('hidden');
        }
        hideError();

        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`,
                {
                    headers: {
                        'User-Agent': 'ReservationApp/1.0'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('Search error');
            }

            const results = await response.json();

            if (results.length === 0) {
                showError(t.geocode_not_found || 'Address not found. Please check or enter coordinates manually.');
                return;
            }

            const result = results[0];
            latitudeInput.value = parseFloat(result.lat).toFixed(8);
            longitudeInput.value = parseFloat(result.lon).toFixed(8);

            // Trigger change event for any listeners
            latitudeInput.dispatchEvent(new Event('change'));
            longitudeInput.dispatchEvent(new Event('change'));

        } catch (error) {
            console.error('Geocoding error:', error);
            showError(t.geocode_error || 'Search error. Please try again.');
        } finally {
            geocodeButton.disabled = false;
            if (geocodeLoading) {
                geocodeLoading.classList.add('hidden');
            }
        }
    });

    function showError(message) {
        if (geocodeError) {
            geocodeError.textContent = message;
            geocodeError.classList.remove('hidden');
        }
    }

    function hideError() {
        if (geocodeError) {
            geocodeError.classList.add('hidden');
        }
    }
});
