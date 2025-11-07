document.addEventListener("DOMContentLoaded", () => {
    const carrinhoContainer = document.querySelector("#carrinho-container");

    // Função para carregar o carrinho via AJAX
    function carregarCarrinho() {
        fetch("carrinho_fragment.php")
            .then(response => {
                if (!response.ok) throw new Error("Erro ao carregar carrinho.");
                return response.text();
            })
            .then(html => {
                carrinhoContainer.innerHTML = html;
                aplicarEstiloCarrinho();
            })
            .catch(error => {
                console.error(error);
                carrinhoContainer.innerHTML = `
                    <div class="erro-carrinho">
                        <p>Não foi possível carregar o carrinho. Tente novamente mais tarde.</p>
                    </div>
                `;
            });
    }

    // Função para aplicar o fundo e restaurar o layout
    function aplicarEstiloCarrinho() {
        document.body.style.background = "#f7f7f7"; // Fundo cinza claro
        document.body.style.minHeight = "100vh";
        document.body.style.display = "flex";
        document.body.style.flexDirection = "column";
        document.body.style.justifyContent = "center";
        document.body.style.alignItems = "center";

        const container = document.querySelector(".carrinho-container");
        if (container) {
            container.style.background = "#fff";
            container.style.boxShadow = "0 2px 8px rgba(0,0,0,0.05)";
            container.style.borderRadius = "10px";
        }

        // Reaplica os eventos dos botões
        document.querySelectorAll(".btn-remover").forEach(botao => {
            botao.addEventListener("click", () => removerItem(botao.dataset.id));
        });
    }

    // Função para remover item via AJAX
    function removerItem(id) {
        fetch("remover_item.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${encodeURIComponent(id)}`
        })
            .then(response => response.text())
            .then(() => carregarCarrinho())
            .catch(err => console.error("Erro ao remover item:", err));
    }

    // Atualiza automaticamente o carrinho ao abrir a página
    carregarCarrinho();
});
