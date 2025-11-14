<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se o usuário é administrador
if (empty($_SESSION['logado']) || $_SESSION['tipo'] !== 'adm') {
    header("Location: ../login.php");
    exit;
}

// Buscar todos os deliveries
$sql = "
    SELECT 
        d.iddelivery,
        d.pedido_id,
        d.status,
        DATE_FORMAT(d.iniciado_em, '%d/%m/%Y %H:%i') AS iniciado_em,
        DATE_FORMAT(d.entregue_em, '%d/%m/%Y %H:%i') AS entregue_em
    FROM delivery d
    ORDER BY d.iddelivery DESC
";

$res = mysqli_query($conexao, $sql);
$deliveries = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Delivery</title>
    <link rel="stylesheet" href="../css/listardelivery.css">
</head>
<body>

<h1>Lista de Deliveries</h1>

<table>
    <thead>
        <tr>
            <th>ID Delivery</th>
            <th>Pedido</th>
            <th>Status</th>
            <th>Iniciado em</th>
            <th>Entregue em</th>
            <th>Ação</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($deliveries as $d): ?>
        <tr>
            <td><?= $d['iddelivery'] ?></td>
            <td>#<?= $d['pedido_id'] ?></td>
            <td><?= ucfirst($d['status']) ?></td>
            <td><?= $d['iniciado_em'] ?></td>
            <td><?= $d['entregue_em'] ?: "-" ?></td>
            
            <td>
                <a class="btn-editar" href="../Forms/formdelivery.php?id=<?= $d['iddelivery'] ?>">Editar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
