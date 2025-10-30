<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if (!$idcliente) {
    die("Cliente não encontrado.");
}

if (!isset($_POST['idproduto']) || !is_array($_POST['idproduto'])) {
    die("Nenhum produto selecionado.");
}

foreach ($_POST['idproduto'] as $idproduto) {
    $quantidade = intval($_POST['quantidade'][$idproduto] ?? 1);

    // Verificar se já existe no carrinho
    $sql_check = "SELECT idcarrinho, quantidade FROM carrinho WHERE idcliente = ? AND idproduto = ?";
    $stmt_check = mysqli_prepare($conexao, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $idcliente, $idproduto);
    mysqli_stmt_execute($stmt_check);
    $res = mysqli_stmt_get_result($stmt_check);

    if ($row = mysqli_fetch_assoc($res)) {
        // Atualiza a quantidade existente
        $nova_qtd = $row['quantidade'] + $quantidade;
        $sql_upd = "UPDATE carrinho 
                    SET quantidade = ?, data_adicionado = NOW() 
                    WHERE idcarrinho = ?";
        $stmt_upd = mysqli_prepare($conexao, $sql_upd);
        mysqli_stmt_bind_param($stmt_upd, "ii", $nova_qtd, $row['idcarrinho']);
        mysqli_stmt_execute($stmt_upd);
    } else {
        // Insere novo item
        $sql_ins = "INSERT INTO carrinho (idproduto, idcliente, quantidade, data_adicionado)
                    VALUES (?, ?, ?, NOW())";
        $stmt_ins = mysqli_prepare($conexao, $sql_ins);
        mysqli_stmt_bind_param($stmt_ins, "iii", $idproduto, $idcliente, $quantidade);
        mysqli_stmt_execute($stmt_ins);
    }
}

// Redireciona para o carrinho
header("Location: carrinho.php");
exit;
