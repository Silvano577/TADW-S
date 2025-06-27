<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Coleta os dados do POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$delivery = isset($_POST['delivery']) ? intval($_POST['delivery']) : 0;
$cliente = isset($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$idfeedback = isset($_POST['idfeedback']) && $_POST['idfeedback'] !== '' ? intval($_POST['idfeedback']) : null;
$idpagamento1 = isset($_POST['idpagamento1']) ? intval($_POST['idpagamento1']) : 0;
$valortotal = isset($_POST['valortotal']) ? floatval($_POST['valortotal']) : 0.00;

if ($id > 0) {
    // Atualização do pedido
    $sucesso = atualizar_pedido($conexao, $id, $delivery, $cliente, $idfeedback, $idpagamento1, $valortotal);
} else {
    // Criação de novo pedido
    $idpedido = salvarPedido($conexao, $delivery, $cliente, $idpagamento1, $valortotal, $idfeedback);
    $sucesso = $idpedido > 0;
}

if ($sucesso) {
    header("Location: ../home.php");
    exit;
} else {
    echo "Erro ao salvar o pedido.";
}
