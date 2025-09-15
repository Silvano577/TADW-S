<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pedido_id = intval($_POST['pedido_id']);
$status = $_POST['status'];

if ($id > 0) {
    atualizar_delivery($conexao, $id, $status);
} else {
    // Novo delivery começa no status informado
    $novo = criar_delivery($conexao, $pedido_id);
    if ($status != 'atribuido') {
        atualizar_delivery($conexao, $novo, $status);
    }
}

header("Location: ../Listar/listardelivery.php");
exit;
?>
