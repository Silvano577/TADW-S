<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    echo json_encode(["sucesso" => false, "mensagem" => "Usuário não logado"]);
    exit;
}

$idcarrinho = intval($_POST['idcarrinho'] ?? 0);

if ($idcarrinho <= 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID inválido"]);
    exit;
}

$sql = "DELETE FROM carrinho WHERE idcarrinho = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcarrinho);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => mysqli_stmt_error($stmt)]);
}
