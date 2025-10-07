<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não houver carrinho, redireciona ou mostra mensagem
if (empty($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    echo "<p>Seu carrinho está vazio. <a href='../cardapio.php'>Voltar ao cardápio</a></p>";
    exit;
}

// Preparar dados do pedido a partir da sessão
$carrinho = $_SESSION['carrinho'];
$total = 0.0;
$itens = [];

foreach ($carrinho as $id => $qtd) {
    $id = intval($id);
    $qtd = max(1, intval($qtd));
    $produto = pesquisarProdutoId($conexao, $id); // ajusta conforme sua função
    if (!$produto) continue;
    $preco = (float)$produto['preco'];
    $subtotal = $preco * $qtd;
    $total += $subtotal;
    $itens[] = [
        'id' => $id,
        'nome' => $produto['nome'],
        'tamanho' => $produto['tamanho'] ?? '',
        'preco' => $preco,
        'qtd' => $qtd,
        'subtotal' => $subtotal,
    ];
}

// Prefill cliente se estiver em sessão (opcional)
$clienteId = isset($_SESSION['cliente_id']) ? intval($_SESSION['cliente_id']) : '';
$endentregaId = '';
$idpagamento = '';
$botao = "Finalizar Pedido";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1><?php echo $botao; ?></h1>

    <form action="../Salvar/salvarpedido.php" method="post">
        <label>Cliente (ID):</label><br>
        <input type="number" name="cliente" value="<?php echo htmlspecialchars($clienteId); ?>" required><br><br>

        <label>Endereço de Entrega (ID):</label><br>
        <input type="number" name="endentrega" value="<?php echo htmlspecialchars($endentregaId); ?>" required><br><br>

        <label>Pagamento (ID) (opcional):</label><br>
        <input type="number" name="idpagamento" value="<?php echo htmlspecialchars($idpagamento); ?>"><br><br>

        <h3>Resumo do Pedido</h3>
        <table>
            <thead>
                <tr><th>Produto</th><th>Tamanho</th><th>Preço</th><th>Qtd</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $it): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($it['nome']); ?></td>
                        <td><?php echo htmlspecialchars($it['tamanho']); ?></td>
                        <td>R$ <?php echo number_format($it['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $it['qtd']; ?></td>
                        <td>R$ <?php echo number_format($it['subtotal'], 2, ',', '.'); ?></td>
                    </tr>

                    <!-- inputs ocultos para enviar os produtos/quantidades ao salvarpedido.php -->
                    <input type="hidden" name="idproduto[]" value="<?php echo $it['id']; ?>">
                    <input type="hidden" name="quantidade[<?php echo $it['id']; ?>]" value="<?php echo $it['qtd']; ?>">
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></strong></p>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <p><a href="../carrinho.php">Voltar ao carrinho</a></p>
</body>
</html>
