<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// ============================
// 1) Verifica se veio via POST
// ============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido.");
}

// ============================
// 2) Receber dados do formulário
// ============================
$idfeedback = intval($_POST['idfeedback'] ?? 0); // se existir, é edição
$assunto    = trim($_POST['assunto'] ?? '');
$comentario = trim($_POST['comentario'] ?? '');
$nota       = intval($_POST['nota'] ?? 0);
$idpedido   = intval($_POST['idpedido'] ?? 0);

// ============================
// 3) Validação básica
// ============================
if ($idpedido <= 0 || empty($assunto) || empty($comentario)) {
    die("Dados inválidos.");
}

// ============================
// 4) Buscar idcliente pelo pedido
// ============================
$sql_cliente = "SELECT idcliente FROM pedido WHERE idpedido = ?";
$stmt = mysqli_prepare($conexao, $sql_cliente);
mysqli_stmt_bind_param($stmt, "i", $idpedido);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$row) {
    die("Erro: Pedido não encontrado.");
}

$idcliente = $row['idcliente']; // agora temos o cliente correto

// ============================
// 5) Se tiver idfeedback → EDITAR
// ============================
if ($idfeedback > 0) {

    $sql = "UPDATE feedback 
            SET assunto = ?, comentario = ?, nota = ?
            WHERE idfeedback = ? AND cliente_id = ?";

    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ssiii", $assunto, $comentario, $nota, $idfeedback, $idcliente);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: ../meus_pedidos.php?editado=ok");
        exit;
    } else {
        mysqli_stmt_close($stmt);
        die("Erro ao atualizar feedback.");
    }
}

// ============================
// 6) Se NÃO tiver idfeedback → CRIAR
// ============================
$sql = "INSERT INTO feedback (assunto, comentario, nota, cliente_id, idpedido)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ssiii", $assunto, $comentario, $nota, $idcliente, $idpedido);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: ../meus_pedidos.php?feedback=ok");
    exit;
} else {
    mysqli_stmt_close($stmt);
    die("Erro ao salvar feedback.");
}
?>
