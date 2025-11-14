<?php
require_once "../conexao.php";
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Delivery inválido.");
}

$iddelivery = intval($_GET['id']);

/*
    1) Buscar qual pedido pertence a este delivery
*/
$sql = "SELECT pedido_id FROM delivery WHERE iddelivery = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $iddelivery);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$delivery = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$delivery) {
    die("Delivery não encontrado.");
}

$pedido_id = $delivery['pedido_id'];

/*
    2) Atualizar o status do delivery para ENTREGUE
*/
$sql = "UPDATE delivery 
        SET status = 'entregue', entregue_em = NOW()
        WHERE iddelivery = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $iddelivery);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/*
    3) Atualizar o status de pagamento para CONCLUIDO
*/
$sql = "UPDATE pagamento 
        SET status_pagamento = 'concluido'
        WHERE idpedido = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $pedido_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/*
    4) Redirecionar para a página anterior ou lista
*/
$voltar = $_SERVER['HTTP_REFERER'] ?? "../Listar/listardelivery.php";
header("Location: $voltar");
exit;
?>
