document.addEventListener('DOMContentLoaded', () => {
    const botoes = document.querySelectorAll('.btn-adicionar');
    const iframeCarrinho = document.getElementById('carrinho-frame');

    botoes.forEach(botao => {
        botao.addEventListener('click', () => {
            const idProduto = botao.dataset.id;
            const qtdInput = document.getElementById('qtd' + idProduto);
            const quantidade = parseInt(qtdInput.value) || 1;

            // Envia via AJAX
            fetch('Forms/adicionar_carrinho.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `idproduto=${idProduto}&quantidade=${quantidade}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    // Atualiza o iframe do carrinho
                    iframeCarrinho.contentWindow.location.reload();
                } else {
                    alert(data.mensagem || 'Erro ao adicionar produto.');
                }
            })
            .catch(err => console.error(err));
        });
    });
});
