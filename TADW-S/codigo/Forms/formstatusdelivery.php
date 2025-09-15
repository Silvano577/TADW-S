<?php
require_once "../conexao.php";
require_once "../funcao.php";
$deliveries = listar_deliveries($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento de Deliveries</title>
</head>
<body>
    <h2>Acompanhamento de Pedidos (Delivery)</h2>
    <table >
        <tr>
            <th>ID Delivery</th>
            <th>Pedido</th>
            <th>Cliente</th>
            <th>Status</th>
            <th>Iniciado em</th>
            <th>Entregue em</th>
            <th>Ação</th>
        </tr>
        <?php foreach($deliveries as $d): ?>
        <tr>
            <td><?= $d['iddelivery'] ?></td>
            <td><?= $d['idpedido'] ?></td>
            <td><?= $d['cliente'] ?></td>
            <td><?= $d['status'] ?></td>
            <td><?= $d['iniciado_em'] ?></td>
            <td><?= $d['entregue_em'] ?></td>
            <td>
                <form action="../Salvar/salvarstatusdelivery.php" method="post">
                    <input type="hidden" name="pedido_id" value="<?= $d['idpedido'] ?>">
                    <select name="status">
                        <option value="atribuido">Atribuído</option>
                        <option value="a_caminho">A Caminho</option>
                        <option value="entregue">Entregue</option>
                        <option value="falha">Falha</option>
                    </select>
                    <button type="submit">Atualizar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
