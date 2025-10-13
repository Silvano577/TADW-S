<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "conexao.php";
require_once "funcao.php";

if (!function_exists('pesquisarProdutoId')) {
    die("Erro: função pesquisarProdutoId() não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
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
              </tr></thead><tbody>";

        foreach ($_SESSION['carrinho'] as $id => $quantidade) {
            $produto = pesquisarProdutoId($conexao, intval($id));
            if (!$produto) continue;

            $quantidade = max(1, intval($quantidade));
            $preco = (float)($produto['preco'] ?? 0);
            $subtotal = $preco * $quantidade;
            $total += $subtotal;

            echo "<tr>
                    <td>".htmlspecialchars($produto['tipo'] ?? '-')."</td>
                    <td>".htmlspecialchars($produto['nome'] ?? '-')."</td>
                    <td>".htmlspecialchars($produto['tamanho'] ?? '-')."</td>
                    <td>R$ ".number_format($preco, 2, ',', '.')."</td>
                    <td>{$quantidade}</td>
                    <td>R$ ".number_format($subtotal, 2, ',', '.')."</td>
                    <td><a href='remover.php?id=$id' class='remover'>Remover</a></td>
                  </tr>";
        }

        echo "</tbody></table>";
        echo "<h3 class='total'>Total da compra: R$ " . number_format($total, 2, ',', '.') . "</h3>";

        // Guardar total para o pagamento
        $_SESSION['total_compra'] = $total;

        echo "<p class='acoes'>
                <a href='destruir.php' class='limpar' onclick=\"return confirm('Deseja esvaziar o carrinho?')\">🧹 Limpar Carrinho</a>
                <a href='cardapio.php' class='voltar'>⬅️ Voltar ao Cardápio</a>
                <a href='../Forms/formpagamento.php' class='finalizar'>✅ Finalizar Pedido</a>
              </p>";
    }
    ?>
</div>
</body>
</html>
