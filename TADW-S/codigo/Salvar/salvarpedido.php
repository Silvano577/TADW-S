<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$delivery = $_POST['delivery'] ?? '';
$cliente = $_POST['cliente'] ?? '';
$idfeedback = $_POST['idfeedback'] ?? '';
$idpagamento1 = $_POST['idpagamento1'] ?? '';

// Decide se é criar ou atualizar
if ($id > 0) {
    atualizar_pedido($conexao, $id, $delivery, $cliente, $idfeedback, $idpagamento1);
} else {
    criar_pedido($conexao, $delivery, $cliente, $idfeedback, $idpagamento1);
}

// Redireciona para a home
header("Location: ../home.php");
exit;

?>
