<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

if (deletar_delivery($conexao, $id)) {
    header("Location: ../Listar/listardelivery.php");
} else {
    header("Location: ../home.php");
}
exit;
?>
