<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Se quiser filtrar por cliente, pode usar GET ou sessão
$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : null;

// Função para listar todos os pedidos (opcionalmente por cliente)

$pedidos = listar_pedidos($conexao, $cliente_id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
</head>
<body>
    <h1>Pedidos <?= $cliente_id ? "do Cliente #$cliente_id" : "" ?></h1>

    <?php if (!empty($pedidos)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Valor Total</th>
                <th>Status Pedido</th>
                <th>Status Delivery</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $p): ?>
            <tr>
                <td><?= $p['idpedido'] ?></td>
                <td><?= $p['cliente'] ?></td>
                <td>R$ <?= number_format($p['valortotal'],2,',','.') ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td><?= htmlspecialchars($p['status_delivery'] ?? 'sem delivery') ?></td>
                <td>
                    <a class="btn" href="listarpedido.php?id=<?= $p['idpedido'] ?>">Ver Detalhes</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>

    <form action="../home.php" method="get" style="margin-top:20px;">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
