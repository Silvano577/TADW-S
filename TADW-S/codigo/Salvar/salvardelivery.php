<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$endereco_entrega = $_POST['endereco_entrega'];
$tempo_entrega = $_POST['tempo_entrega'];

// Decide se é criar ou atualizar
if ($id > 0) {
    atualizar_delivery($conexao, $id, $endereco_entrega, $tempo_entrega);
} else {
    criar_delivery($conexao, $endereco_entrega, $tempo_entrega);
}

// Redireciona para a home
header("Location: ../home.php");
exit;

?>
