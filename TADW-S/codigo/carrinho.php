<?php
// carrinho.php revisado — mudanças mínimas para robustez e segurança

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "conexao.php";
require_once "funcao.php";

// checar se a função de busca existe
if (!function_exists('pesquisarProdutoId')) {
    // mensagem curta — ajuste ou adicione a função em funcao.php
    die("Erro: função pesquisarProdutoId() não encontrada. Verifique funcao.php.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="../css/carrinho.css">
</head>
<body>
    <div class="container">
        <h1>🛍️ Carrinho de Compras</h1>

        <?php
        if (empty($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
            echo "<p class='vazio'>Seu carrinho está vazio.</p>";
        } else {
            $total = 0.0;
            echo "<table>";
            echo "<thead><tr>
                    <th>Tipo</th>
                    <th>Produto</th>
                    <th>Tamanho</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ação</th>
                  </tr></thead>";
            echo "<tbody>";

            foreach ($_SESSION['carrinho'] as $id => $quantidade) {
                $id = intval($id);
                $quantidade = intval($quantidade);
                if ($quantidade < 1) $quantidade = 1;

                $produto = pesquisarProdutoId($conexao, $id);
                if (!$produto) {
                    // produto não existe mais — pula linha
                    continue;
                }

                // campos seguros
                $tipo   = htmlspecialchars($produto['tipo'] ?? '-');
                $nome      = htmlspecialchars($produto['nome'] ?? 'Produto sem nome');
                $tamanho   = htmlspecialchars($produto['tamanho'] ?? '-');
                $preco     = isset($produto['preco']) ? (float)$produto['preco'] : 0.0;
                $subtotal  = $preco * $quantidade;
                $total    += $subtotal;

                $preco_fmt    = "R$ " . number_format($preco, 2, ',', '.');
                $subtotal_fmt = "R$ " . number_format($subtotal, 2, ',', '.');

                $remover_url = 'remover.php?id=' . urlencode((string)$id);

                echo "<tr>";
                echo "<td>{$tipo}</td>";
                echo "<td>{$nome}</td>";
                echo "<td>{$tamanho}</td>";
                echo "<td>{$preco_fmt}</td>";
                echo "<td>{$quantidade}</td>";
                echo "<td>{$subtotal_fmt}</td>";
                echo "<td><a href=\"{$remover_url}\" class=\"remover\">Remover</a></td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

            echo "<h3 class='total'>Total da compra: R$ " . number_format($total, 2, ',', '.') . "</h3>";

            // botões/links (mantive os nomes dos ficheiros que você usou)
            echo "<p class='acoes'>";
            echo "<a href='destruir.php' class='limpar' onclick=\"return confirm('Deseja esvaziar o carrinho?')\">🧹 Limpar Carrinho</a> ";
            echo "<a href='cardapio.php' class='voltar'>⬅️ Voltar ao Cardápio</a> ";
            echo "<a href='../Forms/formpedido.php' class='finalizar'>✅ Finalizar Pedido</a>";
            echo "</p>";
        }
        ?>

    </div>
</body>
</html>
