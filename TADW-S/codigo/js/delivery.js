// Coordenadas da empresa: -15.350444, -49.5965
const empresa = { lat: -15.350444, lng: -49.5965 };

// Inputs de endereço
const ruaInput = document.getElementById('rua');
const numeroInput = document.getElementById('numero');
const bairroInput = document.getElementById('bairro');
const cidadeInput = document.getElementById('cidade');
const cepInput = document.getElementById('cep');

const tempoEntregaSpan = document.getElementById('tempo_entrega');
const tempoEntregaMinInput = document.getElementById('tempo_entrega_min');
const latitudeInput = document.getElementById('latitude');
const longitudeInput = document.getElementById('longitude');

// Cria endereço completo
function getEnderecoCompleto() {
    return `${ruaInput.value}, ${numeroInput.value}, ${bairroInput.value}, ${cidadeInput.value}, ${cepInput.value}`;
}

// Autocomplete baseado na rua
const input = ruaInput;
const autocomplete = new google.maps.places.Autocomplete(input, {
    types: ['address'],
    componentRestrictions: { country: "br" }
});

autocomplete.addListener('place_changed', function() {
    const place = autocomplete.getPlace();
    if (!place.geometry) return;

    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();
    latitudeInput.value = lat;
    longitudeInput.value = lng;

    const service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
        origins: [empresa],
        destinations: [{lat: lat, lng: lng}],
        travelMode: 'DRIVING',
        unitSystem: google.maps.UnitSystem.METRIC
    }, (response, status) => {
        if (status === 'OK') {
            const result = response.rows[0].elements[0];
            if(result.status === "OK"){
                const minutos = Math.ceil(result.duration.value / 60);
                tempoEntregaMinInput.value = minutos;
                tempoEntregaSpan.innerText = minutos + " min";
            } else {
                tempoEntregaMinInput.value = 0;
                tempoEntregaSpan.innerText = "Não disponível";
            }
        }
    });
});
