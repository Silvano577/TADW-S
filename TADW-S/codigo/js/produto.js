function mostrarTamanho(tipo) {
    const campo = document.getElementById('campo-tamanho');
    const input = document.getElementById('input-tamanho');

    if (tipo === 'pizza') {
        campo.style.display = 'block';
        input.removeAttribute('readonly');
    } else {
        campo.style.display = 'none';
        input.setAttribute('readonly', true);
        input.value = '';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const tipoSelect = document.getElementById('tipo-select');
    
    // Mostra tamanho ao carregar a página
    mostrarTamanho(tipoSelect.value);

    // Mostra ou esconde o campo quando o select muda
    tipoSelect.addEventListener('change', () => {
        mostrarTamanho(tipoSelect.value);
    });
});
