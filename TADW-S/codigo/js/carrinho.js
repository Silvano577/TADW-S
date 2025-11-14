document.addEventListener("DOMContentLoaded", () => {
    const tabela = document.getElementById("tabela-carrinho");

    if (!tabela) return;

    tabela.addEventListener("click", async (e) => {
        const btn = e.target.closest("button");
        if (!btn) return;

        const linha = btn.closest("tr");
        const idcarrinho = linha.dataset.idcarrinho;
        const qtdSpan = linha.querySelector(".quantidade");
        let quantidade = parseInt(qtdSpan.textContent);
        const preco = parseFloat(linha.dataset.preco.replace(",", "."));

        let acao = "";
        if (btn.classList.contains("btn-aumentar")) acao = "mais";
        else if (btn.classList.contains("btn-diminuir")) acao = "menos";
        else if (btn.classList.contains("btn-remover")) {
            if (!confirm("Deseja remover este item?")) return;
            acao = "menos";
        } else return;

        try {
            const formData = new FormData();
            formData.append("idcarrinho", idcarrinho);
            formData.append("acao", acao);

            const res = await fetch("../Forms/atualizar_carrinho_ajax.php", { method: "POST", body: formData });
            const data = await res.json();

            if (!data.sucesso) {
                alert(data.mensagem || "Erro ao atualizar o carrinho");
                return;
            }

            // Atualiza quantidade no frontend
            quantidade = data.quantidade;
            if (quantidade === 0) {
                linha.remove();
            } else {
                qtdSpan.textContent = quantidade;

                // Atualiza subtotal local
                const subtotal = preco * quantidade;
                linha.querySelector(".subtotal").textContent = "R$ " + subtotal.toFixed(2).replace(".", ",");
            }

            // Atualiza total geral
            const linhas = tabela.querySelectorAll("tr[data-idcarrinho]");
            let total = 0;
            linhas.forEach(linha => {
                const subtotal = parseFloat(linha.querySelector(".subtotal").textContent.replace("R$ ", "").replace(",", "."));
                total += subtotal;
            });
            document.getElementById("total-geral").textContent = "R$ " + total.toFixed(2).replace(".", ",");
        } catch (err) {
            console.error(err);
            alert("Erro na requisição");
        }
    });
});
