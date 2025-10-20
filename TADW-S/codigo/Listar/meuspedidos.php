<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// 🔹 Verifica se usuário está logado
$usuario_id = $_SESSION['idusuario'] ?? 0;
if (!$usuario_id) {
    header("Location: ../login.php");
    exit;
}

// 🔹 Busca cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
if (!$cliente) die("Erro: cliente não encontrado.");

$idcliente = $cliente['idcliente'];

// 🔹 Buscar pedidos do cliente
$sql = "SELECT p.idpedido, p.data_pedido, p.status, p.valortotal, e.rua, e.numero, e.bairro 
        FROM pedido p
        INNER JOIN endentrega e ON p.identrega = e.idendentrega
        WHERE p.idcliente = ?
        ORDER BY p.data_pedido DESC";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Meus Pedidos</h1>

<?php if (mysqli_num_rows($result) === 0): ?>
    <p>Você ainda não realizou pedidos.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Data</th>
                <th>Status</th>
                <th>Valor Total</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($pedido = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $pedido['idpedido'] ?></td>
                <td><?= $pedido['data_pedido'] ?></td>
                <td><?= ucfirst($pedido['status']) ?></td>
                <td>R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($pedido['rua'] . ', ' . $pedido['numero'] . ' - ' . $pedido['bairro']) ?></td>
                <td>
                    <!-- Botão para editar -->
                    <?php if ($pedido['status'] === 'pendente'): ?>
                        <a href="../Forms/editarpedido.php?id=<?= $pedido['idpedido'] ?>">✏️ Editar</a> |
                        <a href="../Deletar/deletarpedido.php?id=<?= $pedido['idpedido'] ?>" onclick="return confirm('Tem certeza que deseja cancelar este pedido?');">❌ Cancelar</a>
                    <?php else: ?>
                        ---
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="../perfil.php">Voltar ao Perfil</a>
</body>
</html>
