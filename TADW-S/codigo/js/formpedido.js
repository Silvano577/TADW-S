document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-pedido');
    const selectCliente = document.getElementById('select_cliente');
    const radioCliente = document.getElementById('entrega_cliente');
    const radioPonto = document.getElementById('entrega_ponto');
    const inputPonto = document.getElementById('input_ponto');

    form.addEventListener('submit', function(e) {
        let entregaId;

        if (radioCliente.checked) {
            entregaId = selectCliente.value;
        } else if (radioPonto.checked) {
            entregaId = inputPonto ? inputPonto.value : null;
        }

        if (!entregaId) {
            alert("Selecione um endereço de entrega.");
            e.preventDefault();
            return;
        }

        let inputEntrega = document.querySelector('input[name="entrega"]');
        if (!inputEntrega) {
            inputEntrega = document.createElement('input');
            inputEntrega.type = 'hidden';
            inputEntrega.name = 'entrega';
            form.appendChild(inputEntrega);
        }
        inputEntrega.value = entregaId;
    });
});
