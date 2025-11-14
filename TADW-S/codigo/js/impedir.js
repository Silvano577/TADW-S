document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const tipoSelect = document.getElementById('tipo-select');
    const campoTamanho = document.getElementById('campo-tamanho');
    const inputTamanho = document.getElementById('input-tamanho');
    const inputPreco = form.querySelector('input[name="preco"]');

    // Função para mostrar/esconder o campo tamanho
    function atualizarCampoTamanho() {
        if (tipoSelect.value === 'pizza') {
            campoTamanho.style.display = 'block';
            inputTamanho.required = true;
        } else {
            campoTamanho.style.display = 'none';
            inputTamanho.required = false;
            inputTamanho.value = '';
        }
    }

    // Inicializa exibição correta ao carregar a página
    atualizarCampoTamanho();

    // Atualiza quando o tipo é alterado
    tipoSelect.addEventListener('change', atualizarCampoTamanho);

    // Previne envio do form com valores inválidos
    form.addEventListener('submit', (e) => {
        let erros = [];

        // Validação de preço
        if (inputPreco.value === '' || parseFloat(inputPreco.value) < 0) {
            erros.push('O preço não pode ser negativo ou vazio.');
        }

        // Validação do nome
        const inputNome = form.querySelector('input[name="nome"]');
        if (!inputNome.value.trim()) {
            erros.push('O campo nome não pode estar vazio.');
        }

        // Validação do tipo
        if (!tipoSelect.value) {
            erros.push('Selecione um tipo de produto.');
        }

        // Validação do tamanho se for pizza
        if (tipoSelect.value === 'pizza' && !inputTamanho.value.trim()) {
            erros.push('O campo tamanho é obrigatório para pizzas.');
        }

        // Se houver erros, impede envio e mostra alerta
        if (erros.length > 0) {
            e.preventDefault();
            alert(erros.join('\n'));
        }
    });

    // Impede digitar valores negativos no input de preço
    inputPreco.addEventListener('input', () => {
        if (parseFloat(inputPreco.value) < 0) {
            inputPreco.value = '';
        }
    });
});
