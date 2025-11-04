<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

header('Content-Type: application/json');

// 🧩 1. Verificar login
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    echo json_encode(["sucesso" => false, "mensagem" => "Você precisa estar logado."]);
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if (!$idcliente) {
    echo json_encode(["sucesso" => false, "mensagem" => "Cliente não encontrado."]);
    exit;
}

// 🧩 2. Dados enviados via POST
$idproduto = intval($_POST['idproduto'] ?? 0);
$quantidade = intval($_POST['quantidade'] ?? 1);

if ($idproduto <= 0 || $quantidade <= 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos."]);
    exit;
}

// 🧩 3. Verificar se o produto existe
$sql = "SELECT idproduto, preco FROM produto WHERE idproduto = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idproduto);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
    echo json_encode(["sucesso" => false, "mensagem" => "Produto não encontrado."]);
    exit;
}

// 🧩 4. Verificar se já existe no carrinho
$sql_check = "SELECT idcarrinho, quantidade FROM carrinho WHERE idcliente = ? AND idproduto = ?";
$stmt_check = mysqli_prepare($conexao, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $idcliente, $idproduto);
mysqli_stmt_execute($stmt_check);
$res_check = mysqli_stmt_get_result($stmt_check);
$existe = mysqli_fetch_assoc($res_check);

if ($existe) {
    // Já existe -> somar quantidade
    $nova_qtd = $existe['quantidade'] + $quantidade;
    $sql_update = "UPDATE carrinho SET quantidade = ? WHERE idcarrinho = ?";
    $stmt_update = mysqli_prepare($conexao, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ii", $nova_qtd, $existe['idcarrinho']);
    $ok = mysqli_stmt_execute($stmt_update);
} else {
    // Novo item -> inserir
    $sql_insert = "INSERT INTO carrinho (idcliente, idproduto, quantidade) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($conexao, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "iii", $idcliente, $idproduto, $quantidade);
    $ok = mysqli_stmt_execute($stmt_insert);
}

// 🧩 5. Retornar resposta
if ($ok) {
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao adicionar ao carrinho: " . mysqli_error($conexao)
    ]);
}
