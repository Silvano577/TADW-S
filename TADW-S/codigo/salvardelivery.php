<?php

require_once "conexao.php";
require_once "funcao.php";

$endereco_entrega = $_POST['endereco_entrega'];
$tempo_entrega = $_POST['tempo_entrega'];

$id=0;
    criar_delivery($conexao, $endereco_entrega, $tempo_entrega);

header("Location: formdelivery.php");
?>