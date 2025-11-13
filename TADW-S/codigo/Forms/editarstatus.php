<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se o usuário é administrador
if (empty($_SESSION['logado']) || $_SESSION['tipo'] !== 'adm') {
    header("Location: ../login.php");
    exit;
}

// Recebe o ID do pedido
$idpedido = intval($_GET['id'] ?? 0);
if ($idpedido <= 0) {
    die("Pedido inválido.");
}

// Buscar pedido usando função
$pedido = buscar_pedido($conexao, $idpedido);
if (!$pedido) {
    die("Pedido não encontrado.");
}

// Processa atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_status = $_POST['status'] ?? '';
    if (in_array($novo_status, ['pendente', 'preparando', 'pronto', 'cancelado'])) {
        $sql = "UPDATE pedido SET status = ? WHERE idpedido = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "si", $novo_status, $idpedido);
        mysqli_stmt_execute($stmt);

        header("Location: ../Listar/listarpedido.php"); // Redireciona para lista de pedidos do ADM
        exit;
    } else {
        $erro = "Status inválido.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Status do Pedido #<?= $pedido['idpedido'] ?></title>
    <link rel="stylesheet" href="../css/editarstatus.css">
</head>
<body>

<div class="form-container">
    <h1>Editar Status do Pedido #<?= $pedido['idpedido'] ?></h1>

    <?php if (!empty($erro)): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="status">Status do Pedido:</label>
        <select name="status" id="status" required>
            <option value="pendente" <?= $pedido['status_pedido'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
            <option value="preparando" <?= $pedido['status_pedido'] === 'preparando' ? 'selected' : '' ?>>Preparando</option>
            <option value="pronto" <?= $pedido['status_pedido'] === 'pronto' ? 'selected' : '' ?>>Pronto</option>
            <option value="cancelado" <?= $pedido['status_pedido'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
        </select>

        <div class="botoes">
            <button type="submit" class="btn-salvar">Atualizar</button>
            <a href="../Listar/listarpedido.php" class="btn-cancelar">Voltar</a>
        </div>
    </form>
</div>

</body>
</html>
