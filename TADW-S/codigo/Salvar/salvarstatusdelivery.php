<?php
require_once "../conexao.php";
require_once "../funcao.php";

$pedido_id = $_POST['pedido_id'];
$status = $_POST['status'];

if(atualizar_delivery($conexao, $pedido_id, $status)) {
    header("Location: ../Form/formdelivery.php");
    exit;
} else {
    echo "Erro ao atualizar delivery.";
}
