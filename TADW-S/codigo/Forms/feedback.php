<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    die("Acesso negado.");
}

$usuario_id = $_SESSION['idusuario'];
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);

if (!$cliente) {
    die("Cliente não encontrado.");
}

$idcliente = $cliente['idcliente'];

/*
======================================
 VERIFICA SE É EDIÇÃO OU CADASTRO
======================================
*/

// 1) EDIÇÃO (idfeedback)
$idfeedback = intval($_GET['id'] ?? 0);

// 2) CADASTRO (idpedido)
$idpedido = intval($_GET['idpedido'] ?? 0);

$modo = "";
$dados = [
    "assunto" => "",
    "comentario" => "",
    "nota" => 0,
    "idpedido" => 0
];

// -------------------
// MODO EDIÇÃO
// -------------------
if ($idfeedback > 0) {

    $sql = "SELECT * FROM feedback WHERE idfeedback = ? AND cliente_id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $idfeedback, $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $fb = mysqli_fetch_assoc($res);

    if (!$fb) {
        die("Feedback não encontrado.");
    }

    $modo = "editar";
    $dados = $fb;
    $idpedido = $fb['idpedido'];
}

// -------------------
// MODO CADASTRO
// -------------------
elseif ($idpedido > 0) {

    // Verifica se pedido pertence ao cliente
    $sql = "SELECT idpedido FROM pedido WHERE idpedido = ? AND idcliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $idpedido, $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!mysqli_fetch_assoc($res)) {
        die("Pedido inválido.");
    }

    $modo = "cadastrar";
    $dados['idpedido'] = $idpedido;

} else {
    die("Acesso inválido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $modo === "editar" ? "Editar Feedback" : "Novo Feedback" ?></title>
    <link rel="stylesheet" href="../css/feedback.css">
</head>
<body>



<form action="../Salvar/salvarfeedback.php" method="post">
    <h1><?= $modo === "editar" ? "Editar Feedback" : "Criar Feedback" ?></h1>
    <input type="hidden" name="idpedido" value="<?= $dados['idpedido'] ?>">
    <?php if ($modo === "editar"): ?>
        <input type="hidden" name="idfeedback" value="<?= $dados['idfeedback'] ?>">
    <?php endif; ?>

    <label>Assunto:</label>
    <input type="text" name="assunto" value="<?= htmlspecialchars($dados['assunto']) ?>" required>

    <label>Comentário:</label>
    <textarea name="comentario" required><?= htmlspecialchars($dados['comentario']) ?></textarea>

    <label>Nota (0 a 10):</label>
    <input type="number" name="nota" min="0" max="10" value="<?= $dados['nota'] ?>" required>

    <button type="submit">
        <?= $modo === "editar" ? "Salvar Alterações" : "Enviar Feedback" ?>
    </button>
        <a href="../meus_pedidos.php">Voltar</a>
</form>



</body>
</html>
