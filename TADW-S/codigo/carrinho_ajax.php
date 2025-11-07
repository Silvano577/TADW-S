<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não logado']);
    exit;
}

$usuario_id = $_SESSION['idusuario'];
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
if (!$cliente) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Cliente não encontrado']);
    exit;
}

if (isset($_POST['idcarrinho']) && isset($_POST['acao'])) {
    $idcarrinho = intval($_POST['idcarrinho']);
    $acao = $_POST['acao'];

    $sql = "SELECT quantidade FROM carrinho WHERE idcarrinho = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idcarrinho);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($res);

    if (!$item) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Item não encontrado']);
        exit;
    }

    $quantidade = $item['quantidade'];

    if ($acao === 'mais') $quantidade++;
    elseif ($acao === 'menos' && $quantidade > 1) $quantidade--;

    $sql_upd = "UPDATE carrinho SET quantidade = ?, data_adicionado = NOW() WHERE idcarrinho = ?";
    $stmt_upd = mysqli_prepare($conexao, $sql_upd);
    mysqli_stmt_bind_param($stmt_upd, "ii", $quantidade, $idcarrinho);
    mysqli_stmt_execute($stmt_upd);

    echo json_encode(['sucesso' => true, 'quantidade' => $quantidade]);
    exit;
}

if (isset($_POST['idproduto'])) {
    $idproduto = intval($_POST['idproduto']);
    $quantidade = intval($_POST['quantidade'] ?? 1);

    if ($idproduto <= 0 || $quantidade <= 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
        exit;
    }

    $sql = "SELECT idcarrinho, quantidade FROM carrinho WHERE idcliente = ? AND idproduto = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $cliente['idcliente'], $idproduto);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($item = mysqli_fetch_assoc($res)) {
        $novaQtd = $item['quantidade'] + $quantidade;
        $sqlUp = "UPDATE carrinho SET quantidade = ?, data_adicionado = NOW() WHERE idcarrinho = ?";
        $stmtUp = mysqli_prepare($conexao, $sqlUp);
        mysqli_stmt_bind_param($stmtUp, "ii", $novaQtd, $item['idcarrinho']);
        mysqli_stmt_execute($stmtUp);
    } else {
        $sqlIn = "INSERT INTO carrinho (idcliente, idproduto, quantidade) VALUES (?, ?, ?)";
        $stmtIn = mysqli_prepare($conexao, $sqlIn);
        mysqli_stmt_bind_param($stmtIn, "iii", $cliente['idcliente'], $idproduto, $quantidade);
        mysqli_stmt_execute($stmtIn);
    }

    echo json_encode(['sucesso' => true]);
    exit;
}

echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida']);
