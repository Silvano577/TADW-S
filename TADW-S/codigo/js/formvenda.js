function calcularTotal() {
    let total = 0;
    document.querySelectorAll("input[name^='quantidade']").forEach(qtdInput => {
        let id = qtdInput.name.match(/\d+/)[0];
        let precoElem = qtdInput.closest('.produto').querySelector('p');
        let preco = parseFloat(precoElem.textContent.replace('Preço: R$ ', '').replace('.', '').replace(',', '.'));
        let qtd = parseInt(qtdInput.value) || 0;
        total += preco * qtd;
    });
    document.getElementById('valor_total').textContent = total.toFixed(2).replace('.', ',');
}

// Inicializa o total na página
calcularTotal();
