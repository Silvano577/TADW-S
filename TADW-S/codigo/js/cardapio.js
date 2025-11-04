document.addEventListener('DOMContentLoaded', () => {
    const botoes = document.querySelectorAll('.btn-adicionar');
    const carrinhoContainer = document.getElementById('carrinho-container');

    const urls = {
        adicionar: 'adicionar.php',
        fragmento: 'carrinho_fragment.php',
        contador: 'contador_carrinho.php',
        remover: 'remover_ajax.php',
        atualizar: 'atualizar_carrinho_ajax.php'
    };

    // 🔁 Carrega o conteúdo do carrinho (HTML)
    function carregarCarrinho() {
        fetch(urls.fragmento, { cache: 'no-store' })
            .then(res => res.text())
            .then(html => {
                carrinhoContainer.innerHTML = html;
                adicionarEventosCarrinho();
            })
            .catch(err => console.error('Erro ao carregar carrinho:', err));
    }

    // 🔁 Atualiza contador de itens no topo
    function atualizarContador() {
        fetch(urls.contador)
            .then(res => res.json())
            .then(data => {
                const contador = document.querySelector('.carrinho-status');
                if (contador) contador.textContent = `🛒 ${data.total} itens`;
            })
            .catch(err => console.error('Erro no contador:', err));
    }

    // ➕ Adicionar produto ao carrinho
    botoes.forEach(botao => {
        botao.addEventListener('click', () => {
            const idProduto = botao.dataset.id;
            const qtd = parseInt(document.getElementById('qtd' + idProduto).value) || 1;

            fetch(urls.adicionar, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `idproduto=${idProduto}&quantidade=${qtd}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    atualizarContador();
                    carregarCarrinho();
                } else {
                    alert(data.mensagem || 'Erro ao adicionar produto.');
                }
            })
            .catch(err => console.error('Erro ao adicionar:', err));
        });
    });

    // ⚙️ Funções internas de manipulação do carrinho (incrementar, remover)
    function adicionarEventosCarrinho() {
        // alterar quantidade
        carrinhoContainer.querySelectorAll('.btn-qtd').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const acao = btn.dataset.acao;

                fetch(urls.atualizar, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `idcarrinho=${id}&acao=${acao}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.sucesso) {
                        carregarCarrinho();
                        atualizarContador();
                    } else {
                        alert(data.mensagem || 'Erro ao atualizar.');
                    }
                });
            });
        });

        // remover item
        carrinhoContainer.querySelectorAll('.btn-remover').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                if (!confirm('Remover este item do carrinho?')) return;

                fetch(urls.remover, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `idcarrinho=${id}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.sucesso) {
                        carregarCarrinho();
                        atualizarContador();
                    } else {
                        alert(data.mensagem || 'Erro ao remover item.');
                    }
                });
            });
        });
    }

    // Carregamento inicial
    atualizarContador();
    carregarCarrinho();
});
document.addEventListener("DOMContentLoaded", () => {
    const botoesFiltro = document.querySelectorAll(".btn-filtro");
    const cards = document.querySelectorAll(".card");

    botoesFiltro.forEach(btn => {
        btn.addEventListener("click", () => {
            // Atualiza botão ativo
            botoesFiltro.forEach(b => b.classList.remove("ativo"));
            btn.classList.add("ativo");

            const categoria = btn.getAttribute("data-categoria");

            // Filtra os produtos sem recarregar
            cards.forEach(card => {
                if (categoria === "todas" || card.dataset.categoria === categoria) {
                    card.style.display = "flex";
                    card.style.opacity = "1";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
});
