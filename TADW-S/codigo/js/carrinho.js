document.addEventListener("DOMContentLoaded", () => {
    const tabela = document.getElementById("tabela-carrinho");

    tabela.addEventListener("click", async (e) => {
        const btn = e.target.closest("button");
        if (!btn) return;

        const linha = btn.closest("tr");
        const idcarrinho = linha.dataset.idcarrinho;
        const preco = parseFloat(linha.dataset.preco);

        const qtdSpan = linha.querySelector(".quantidade");
        let quantidade = parseInt(qtdSpan.textContent);

        // Atualizar quantidade
        if (btn.dataset.acao === "mais") {
            quantidade++;
        } else if (btn.dataset.acao === "menos") {
            quantidade = Math.max(1, quantidade - 1);
        } else if (btn.classList.contains("btn-remover")) {
            // Remover item
            if (!confirm("Deseja remover este item?")) return;
            quantidade = 0;
        }

        // Chamada AJAX para atualizar o carrinho
        const formData = new FormData();
        formData.append("idcarrinho", idcarrinho);
        formData.append("quantidade", quantidade);

        const url = quantidade === 0 ? "../Deletar/remover_ajax.php" : "../Forms/atualizar_carrinho_ajax.php";

        try {
            const res = await fetch(url, {
                method: "POST",
                body: formData
            });
            const data = await res.text();

            if (res.ok) {
                if (quantidade === 0) {
                    linha.remove();
                } else {
                    qtdSpan.textContent = quantidade;
                    linha.querySelector(".subtotal").textContent = "R$ " + (preco * quantidade).toFixed(2).replace(".", ",");
                }
                atualizarTotalGeral();
            } else {
                alert("Erro ao atualizar o carrinho");
            }
        } catch (err) {
            console.error(err);
            alert("Erro na requisição");
        }
    });

    function atualizarTotalGeral() {
        const linhas = tabela.querySelectorAll("tr[data-idcarrinho]");
        let total = 0;
        linhas.forEach(linha => {
            const subtotal = linha.querySelector(".subtotal").textContent.replace("R$ ", "").replace(",", ".");
            total += parseFloat(subtotal);
        });
        document.getElementById("total-geral").textContent = "R$ " + total.toFixed(2).replace(".", ",");
    }
});
