<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$delivery = isset($_POST['delivery']) ? intval($_POST['delivery']) : 0;
$cliente = isset($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$idfeedback = isset($_POST['idfeedback']) && $_POST['idfeedback'] !== '' ? intval($_POST['idfeedback']) : null;
$idpagamento1 = isset($_POST['idpagamento1']) ? intval($_POST['idpagamento1']) : 0;
$valortotal = isset($_POST['valortotal']) ? floatval($_POST['valortotal']) : 0.00;

// Decide se é criação ou atualização
if ($id > 0) {
    // Atualizar pedido existente
    atualizar_pedido($conexao, $id, $delivery, $cliente, $idfeedback, $idpagamento1, $valortotal);
} else {
    // Criar novo pedido
    criar_pedido($conexao, $delivery, $cliente, $idfeedback, $idpagamento1, $valortotal);
}

// Redireciona para a home
header("Location: ../home.php");
exit;

?>

