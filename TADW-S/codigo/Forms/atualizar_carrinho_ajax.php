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
$acao = $_POST['acao'] ?? '';

if ($idcarrinho <= 0 || !in_array($acao, ['mais', 'menos'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos"]);
    exit;
}

$sql = "SELECT quantidade FROM carrinho WHERE idcarrinho = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcarrinho);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo json_encode(["sucesso" => false, "mensagem" => "Item não encontrado"]);
    exit;
}

$quantidade = $item['quantidade'];
$quantidade = ($acao === 'mais') ? $quantidade + 1 : max(1, $quantidade - 1);

$sql_update = "UPDATE carrinho SET quantidade = ? WHERE idcarrinho = ?";
$stmt_update = mysqli_prepare($conexao, $sql_update);
mysqli_stmt_bind_param($stmt_update, "ii", $quantidade, $idcarrinho);

if (mysqli_stmt_execute($stmt_update)) {
    echo json_encode(["sucesso" => true, "quantidade" => $quantidade]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => mysqli_stmt_error($stmt_update)]);
}
