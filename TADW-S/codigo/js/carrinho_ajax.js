document.addEventListener("DOMContentLoaded", () => {
    const tabela = document.getElementById("tabela-carrinho");

    if (!tabela) return;

    // Delegação de eventos para todos os botões dentro da tabela
    tabela.addEventListener("click", async (e) => {
        const btn = e.target.closest("button");
        if (!btn) return;

        const linha = btn.closest("tr");
        const idcarrinho = linha.dataset.idcarrinho;
        const qtdSpan = linha.querySelector(".quantidade");
        let quantidade = parseInt(qtdSpan.textContent);

        // Determina ação
        let acao = "";
        if (btn.classList.contains("btn-aumentar")) acao = "mais";
        else if (btn.classList.contains("btn-diminuir")) acao = "menos";
        else if (btn.classList.contains("btn-remover")) acao = "menos"; // trata remover como diminuir até 0
        else return;

        // Confirmar remoção
        if (btn.classList.contains("btn-remover") && !confirm("Deseja remover este item?")) return;

        // Requisição AJAX
        try {
            const formData = new FormData();
            formData.append("idcarrinho", idcarrinho);
            formData.append("acao", acao);

            const res = await fetch("../Forms/atualizar_carrinho_ajax.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();

            if (!data.sucesso) {
                alert(data.mensagem || "Erro ao atualizar o carrinho");
                return;
            }

            // Atualiza a linha ou remove
            if (data.quantidade === 0) {
                linha.remove();
            } else {
                qtdSpan.textContent = data.quantidade;
                const subtotalTd = linha.querySelector(".subtotal");
                subtotalTd.textContent = "R$ " + data.subtotal.toFixed(2).replace(".", ",");
            }

            atualizarTotalGeral();

        } catch (err) {
            console.error(err);
            alert("Erro na requisição");
        }
    });

    // Função para recalcular total geral
    function atualizarTotalGeral() {
        const linhas = tabela.querySelectorAll("tr[data-idcarrinho]");
        let total = 0;

        linhas.forEach(linha => {
            const subtotalText = linha.querySelector(".subtotal").textContent;
            const subtotal = parseFloat(subtotalText.replace("R$ ", "").replace(",", "."));
            total += subtotal;
        });

        const totalGeralSpan = document.getElementById("total-geral");
        if (totalGeralSpan) {
            totalGeralSpan.textContent = "R$ " + total.toFixed(2).replace(".", ",");
        }
    }
});
