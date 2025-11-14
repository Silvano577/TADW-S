<?php
require_once "../conexao.php";
require_once "../funcao.php";

$pedido_id = intval($_POST['pedido_id']);
$status = $_POST['status'];

if ($pedido_id > 0) {
    $sql = "INSERT INTO delivery (pedido_id, status) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "is", $pedido_id, $status);
    mysqli_stmt_execute($stmt);

    header("Location: listardelivery.php");
    exit;
}

echo "Erro ao salvar delivery.";
?>
