<?php

require_once "conexao.php";
require_once "funcao.php";

$delivery = $_POST['delivery'] ?? '';
$cliente = $_POST['cliente'] ?? '';
$idfeedback = $_POST['idfeedback'] ?? '';
$idpagamento1 = $_POST['idpagamento1'] ?? '';



$id=0;
    criar_pedido($conexao, $delivery, $cliente, $idfeedback, $idpagamento1);

header("Location: formpagamento.php");
?>