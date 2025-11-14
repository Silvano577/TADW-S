<?php
require_once "../conexao.php";
require_once "../funcao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $iddelivery = intval($_POST['iddelivery'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($iddelivery <= 0) {
        die("Delivery inválido.");
    }

    // Buscar delivery atual para obter o ID do pedido
    $delivery = buscar_delivery($conexao, $iddelivery);

    if (!$delivery) {
        die("Delivery não encontrado.");
    }

    $pedido_id = $delivery['pedido_id'];

    // Atualiza o status da entrega
    $sql = "UPDATE delivery SET status = ?, 
            entregue_em = IF(? = 'entregue', NOW(), entregue_em)
            WHERE iddelivery = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $status, $status, $iddelivery);
    mysqli_stmt_execute($stmt);

    // ✔ SE A ENTREGA FOR CONCLUÍDA → PAGAMENTO VIRA "concluido"
    if ($status === 'entregue') {
        $sql2 = "UPDATE pagamento SET status_pagamento = 'concluido'
                 WHERE pedido_id = ?";
        $stmt2 = mysqli_prepare($conexao, $sql2);
        mysqli_stmt_bind_param($stmt2, 'i', $pedido_id);
        mysqli_stmt_execute($stmt2);
    }

    // Redireciona para a mesma página de onde veio
    $voltar = $_POST['voltar'] ?? 'listardelivery.php';
    header("Location: $voltar");
    exit;
}
?>
