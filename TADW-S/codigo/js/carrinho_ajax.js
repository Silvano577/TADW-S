document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('carrinho-container');

    if (!container) {
        console.error("❌ Elemento #carrinho-container não encontrado na página.");
        return;
    }

    /**
     * 🔄 Função para carregar o fragmento do carrinho
     */
    function carregarCarrinho() {
        fetch('carrinho_fragment.php', { cache: 'no-store' })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                aplicarEstiloCarrinho();
            })
            .catch(err => {
                console.error('Erro ao carregar carrinho:', err);
                container.innerHTML = `
                    <p style="color:red;text-align:center;padding:20px;">
                        ⚠️ Erro ao carregar o carrinho. Tente novamente.
                    </p>
                `;
            });
    }

    /**
     * 🎨 Garante que o CSS do fragmento seja carregado mesmo após requisições AJAX
     */
    function aplicarEstiloCarrinho() {
        const linkHref = './css/carrinho_frag.css';
        const jaExiste = Array.from(document.styleSheets).some(
            sheet => sheet.href && sheet.href.includes('carrinho_frag.css')
        );

        if (!jaExiste) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = linkHref;
            document.head.appendChild(link);
            console.log('🎨 CSS do carrinho aplicado com sucesso.');
        }
    }

    /**
     * 🔁 Recarrega o carrinho ao atualizar ou interagir
     */
    document.addEventListener('atualizarCarrinho', carregarCarrinho);

    /**
     * 🚀 Carrega o carrinho assim que a página abre
     */
    carregarCarrinho();
});
