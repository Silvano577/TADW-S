<?php
session_start();
require_once "../conexao.php";

if (!isset($_POST['idcarrinho']) || !isset($_POST['acao'])) {
    die("Requisição inválida.");
}

$idcarrinho = intval($_POST['idcarrinho']);
$acao = $_POST['acao'];

// Buscar quantidade atual
$sql = "SELECT quantidade FROM carrinho WHERE idcarrinho = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcarrinho);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    die("Item não encontrado.");
}

$quantidade = $item['quantidade'];

if ($acao === "mais") {
    $quantidade++;
} elseif ($acao === "menos" && $quantidade > 1) {
    $quantidade--;
}

$sql_upd = "UPDATE carrinho SET quantidade = ?, data_adicionado = NOW() WHERE idcarrinho = ?";
$stmt_upd = mysqli_prepare($conexao, $sql_upd);
mysqli_stmt_bind_param($stmt_upd, "ii", $quantidade, $idcarrinho);
mysqli_stmt_execute($stmt_upd);

header("Location: ../carrinho.php");
exit;
