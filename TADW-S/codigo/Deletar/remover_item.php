<?php
session_start();
require_once "../conexao.php";

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "DELETE FROM carrinho WHERE idcarrinho = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: ../carrinho.php");
exit;
