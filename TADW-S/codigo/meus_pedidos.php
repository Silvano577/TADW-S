<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Buscar o cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);

if (!$cliente) {
    echo "<p>Você ainda não possui dados de cliente.</p>";
    exit;
}

$idcliente = $cliente['idcliente'];

// Buscar pedidos do cliente logado
$sql = "
    SELECT 
        p.idpedido,
        p.valortotal,
        p.status AS status_pedido,
        DATE_FORMAT(p.data_pedido, '%d/%m/%Y %H:%i') AS data_pedido,
        pag.status_pagamento AS status_pagamento,
        pag.metodo_pagamento AS metodo_pagamento,
        d.status AS status_delivery
    FROM pedido p
    LEFT JOIN pagamento pag ON p.idpedido = pag.idpedido
    LEFT JOIN delivery d ON p.idpedido = d.pedido_id
    WHERE p.idcliente = ?
    ORDER BY p.idpedido DESC
";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="./css/meus.css">
</head>
<body>
    <h1>Meus Pedidos</h1>

    <?php if (empty($pedidos)): ?>
        <p>Você ainda não fez nenhum pedido.</p>
    <?php else: ?>
        <table >
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Data</th>
                    <th>Status do Pedido</th>
                    <th>Status Pagamento</th>
                    <th>Método de Pagamento</th>
                    <th>Status Entrega</th>
                    <th>Valor Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= $pedido['idpedido'] ?></td>
                        <td><?= $pedido['data_pedido'] ?></td>
                        <td><?= ucfirst($pedido['status_pedido']) ?></td>
                        <td><?= ucfirst($pedido['status_pagamento'] ?? '-') ?></td>
                        <td><?= ucfirst($pedido['metodo_pagamento'] ?? '-') ?></td>
                        <td><?= ucfirst($pedido['status_delivery'] ?? '-') ?></td>
                        <td>R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></td>
                        <td><a href="detalhe_pedido.php?id=<?= $pedido['idpedido'] ?>">Ver Detalhes</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="perfil.php">Voltar ao Perfil</a>
</body>
</html>
