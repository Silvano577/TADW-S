<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se é admin
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim' || $_SESSION['tipo'] !== 'adm') {
    die("Acesso negado. Somente administradores podem acessar esta página.");
}

// Buscar todos os pedidos
$pedidos = listar_pedidos_adm($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Todos os Pedidos</title>
    <link rel="stylesheet" href="../css/listarpedidoadm.css">
</head>
<body>

<h1>📋 Lista de Todos os Pedidos</h1>

<table>
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Cliente</th>
            <th>Data</th>
            <th>Valor Total</th>
            <th>Status Pedido</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($pedidos as $p): ?>
        <tr>
            <td><?= $p['idpedido'] ?></td>
            <td><?= htmlspecialchars($p['nome_cliente']) ?></td>
            <td><?= date("d/m/Y H:i", strtotime($p['data_pedido'])) ?></td>
            <td>R$ <?= number_format($p['valortotal'], 2, ',', '.') ?></td>
            <td><?= ucfirst($p['status_pedido']) ?></td>
            <td>
                <a href="../Forms/editarstatus.php?id=<?= $p['idpedido'] ?>" class="btn-editar">Editar Status</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
