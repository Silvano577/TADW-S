const checkboxes = document.querySelectorAll(".produto-checkbox");
const quantidades = document.querySelectorAll(".produto-quantidade");
const valorTotal = document.getElementById("valor_total");

function calcularTotal() {
    let total = 0;

    checkboxes.forEach(chk => {
        const id = chk.value;
        const precoElem = document.getElementById("preco_" + id);
        const subtotalElem = document.getElementById("subtotal_" + id);
        const qtdElem = document.getElementById("quantidade_" + id);
        const preco = parseFloat(precoElem.textContent.replace(',', '.')) || 0;
        const qtd = parseInt(qtdElem.value) || 0;

        if (chk.checked) {
            const subtotal = preco * qtd;
            subtotalElem.textContent = subtotal.toFixed(2).replace('.', ',');
            total += subtotal;
        } else {
            subtotalElem.textContent = '0,00';
        }
    });

    valorTotal.value = total.toFixed(2).replace('.', ',');
}

// Eventos
checkboxes.forEach(chk => chk.addEventListener('change', calcularTotal));
quantidades.forEach(qtd => qtd.addEventListener('change', calcularTotal));
document.addEventListener("DOMContentLoaded", calcularTotal);

function calcular() {
    let total = 0;
    document.querySelectorAll(".produto-check").forEach(chk => {
        if (chk.checked) {
            let id = chk.value;
            let preco = parseFloat(document.getElementById("preco_" + id).textContent.replace(',', '.'));
            let qtd = parseInt(document.getElementById("quantidade_" + id).value) || 1;
            total += preco * qtd;
        }
    });
    document.getElementById("valor_total").value = total.toFixed(2).replace('.', ',');
}
