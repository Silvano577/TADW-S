<?php
require_once "../conexao.php";
require_once "../funcao.php";
session_start();

// Recebe o ID do pedido pela URL
$idpedido = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idpedido <= 0) {
    die("Pedido inválido.");
}

// ============================
// 1️⃣ Buscar informações do pedido
// ============================
$sqlPedido = "
    SELECT p.idpedido, p.valortotal, p.status AS status_pedido,
           c.nome AS nome_cliente, e.rua, e.numero, e.bairro, e.cidade,
           pag.idpagamento, pag.status AS status_pagamento, pag.valor AS valor_pagamento,
           d.iddelivery, d.status AS status_delivery
    FROM pedido p
    JOIN cliente c ON p.cliente = c.idcliente
    JOIN endereco e ON p.endentrega = e.idendereco
    JOIN pagamento pag ON p.idpagamento = pag.idpagamento
    JOIN delivery d ON d.idpedido = p.idpedido
    WHERE p.idpedido = ?
";

$stmt = mysqli_prepare($conexao, $sqlPedido);
mysqli_stmt_bind_param($stmt, "i", $idpedido);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$pedido) {
    die("Pedido não encontrado.");
}

// ============================
// 2️⃣ Buscar itens do pedido
// ============================
$sqlItens = "
    SELECT pr.nome, pr.preco, pp.quantidade
    FROM pedido_produto pp
    JOIN produto pr ON pr.idproduto = pp.idproduto
    WHERE pp.idpedido = ?
";
$stmtItens = mysqli_prepare($conexao, $sqlItens);
mysqli_stmt_bind_param($stmtItens, "i", $idpedido);
mysqli_stmt_execute($stmtItens);
$itens = mysqli_stmt_get_result($stmtItens);
mysqli_stmt_close($stmtItens);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo do Pedido #<?php echo $idpedido; ?></title>
    <link rel="stylesheet" href="../css/listarpedido.css">
</head>
<body>

<div class="container">
    <h1>Resumo do Pedido #<?php echo $pedido['idpedido']; ?></h1>

    <div class="info-bloco">
        <h2>📋 Dados do Pedido</h2>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nome_cliente']); ?></p>
        <p><strong>Endereço:</strong> <?php echo "{$pedido['rua']}, {$pedido['numero']} - {$pedido['bairro']}, {$pedido['cidade']}"; ?></p>
        <p><strong>Status do Pedido:</strong> <span class="status"><?php echo ucfirst($pedido['status_pedido']); ?></span></p>
    </div>

    <div class="info-bloco">
        <h2>💳 Pagamento</h2>
        <p><strong>ID Pagamento:</strong> <?php echo $pedido['idpagamento']; ?></p>
        <p><strong>Status:</strong> <span class="status"><?php echo ucfirst($pedido['status_pagamento']); ?></span></p>
        <p><strong>Valor:</strong> R$ <?php echo number_format($pedido['valor_pagamento'], 2, ',', '.'); ?></p>
    </div>

    <div class="info-bloco">
        <h2>🚚 Entrega</h2>
        <p><strong>ID Delivery:</strong> <?php echo $pedido['iddelivery']; ?></p>
        <p><strong>Status:</strong> <span class="status"><?php echo ucfirst($pedido['status_delivery']); ?></span></p>
    </div>

    <div class="info-bloco">
        <h2>🍕 Itens do Pedido</h2>
        <table>
            <tr>
                <th>Produto</th>
                <th>Preço Unitário</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $subtotalGeral = 0;
            while ($item = mysqli_fetch_assoc($itens)) {
                $subtotal = $item['preco'] * $item['quantidade'];
                $subtotalGeral += $subtotal;
                echo "<tr>
                        <td>{$item['nome']}</td>
                        <td>R$ " . number_format($item['preco'], 2, ',', '.') . "</td>
                        <td>{$item['quantidade']}</td>
                        <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                    </tr>";
            }
            ?>
        </table>
        <p class="total">💰 Total com entrega: <strong>R$ <?php echo number_format($pedido['valortotal'], 2, ',', '.'); ?></strong></p>
    </div>

    <a href="../index.php" class="btn-voltar">← Voltar ao Início</a>
</div>

</body>
</html>
