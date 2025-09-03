<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_pedidos = listar_pedidos($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Pedidos</h1>

    <?php if (count($lista_pedidos) === 0): ?>
        <p style="text-align:center;">Nenhum pedido encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_pedidos as $pedido): ?>
                <div class="card">
                    <h3>Cliente: <?= htmlspecialchars($pedido['idcliente']) ?></h3>
                    <p>Data: <?= htmlspecialchars($pedido['data_pedido']) ?></p>
                    <p>Status: <?= htmlspecialchars($pedido['status']) ?></p>
                    <a href="../Forms/formpedido.php?id=<?= $pedido['idpedido'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarpedido.php?id=<?= $pedido['idpedido'] ?>" onclick="return confirm('Deseja realmente excluir este pedido?');" class="btn btn-delete">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
