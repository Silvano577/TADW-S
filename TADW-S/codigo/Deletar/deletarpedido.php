<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Captura o ID do pedido via GET
$id = intval($_GET['id']);

// Chama a função de deletar pedido
if (deletar_pedido($conexao, $id)) {
    // Redireciona para a lista de pedidos após exclusão bem-sucedida
    header("Location: ../Listar/listarpedido.php");
} else {
    // Caso dê erro, volta para a home
    header("Location: ../home.php");
}
exit;
?>
