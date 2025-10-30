<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Função para listar vendas com método de pagamento e valor total
function listar_venda($conexao) {
    $sql = "SELECT p.idpedido, pg.metodo_pagamento AS metodo, p.valortotal
            FROM pedido p
            LEFT JOIN pagamento pg ON p.idpedido = pg.idpedido
            ORDER BY p.idpedido DESC";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $vendas = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $vendas;
}

// Função para listar apenas os nomes dos produtos de um pedido
function listar_itens_pedido($conexao, $idpedido) {
    $sql = "SELECT p.nome, pp.quantidade
            FROM pedido_produto pp
            JOIN produto p ON pp.idproduto = p.idproduto
            WHERE pp.idpedido = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idpedido);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $itens = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $itens;
}

// Buscar todas as vendas
$vendas = listar_venda($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Vendas - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Vendas</h1>

    <?php if (empty($vendas)): ?>
        <p style="text-align:center;">Nenhuma venda encontrada.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($vendas as $venda): ?>
                <div class="card">
                    <h3>Pedido: <?= htmlspecialchars($venda['idpedido'] ?? '') ?></h3>
                    <p>Método: <?= htmlspecialchars($venda['metodo'] ?? '-') ?></p>
                    <p>Valor Total: R$ <?= number_format(floatval($venda['valortotal'] ?? 0), 2, ',', '.') ?></p>
                    <p>Produtos:</p>
                    <ul>
                        <?php 
                        $itens = listar_itens_pedido($conexao, $venda['idpedido'] ?? 0);
                        if (!empty($itens)):
                            foreach ($itens as $item): ?>
                                <li><?= htmlspecialchars($item['nome'] ?? '-') ?> (Qtd: <?= intval($item['quantidade'] ?? 0) ?>)</li>
                            <?php endforeach; 
                        else: ?>
                            <li>Nenhum produto</li>
                        <?php endif; ?>
                    </ul>
                    <div class="acoes">
                        <a href="../Forms/formpedido.php?id=<?= $venda['idpedido'] ?>" class="btn">Editar</a>
                        <a href="../Deletar/deletarpedido.php?id=<?= $venda['idpedido'] ?>" onclick="return confirm('Deseja realmente excluir este pedido?');" class="btn btn-delete">Excluir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
