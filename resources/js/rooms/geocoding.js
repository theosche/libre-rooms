// Geocoding functionality using Nominatim API

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
            showError('Veuillez remplir au moins la rue et la ville.');
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
                throw new Error('Erreur lors de la recherche');
            }

            const results = await response.json();

            if (results.length === 0) {
                showError('Adresse non trouvée. Veuillez vérifier ou entrer les coordonnées manuellement.');
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
            showError('Erreur lors de la recherche. Veuillez réessayer.');
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
