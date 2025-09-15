<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

if (deletar_endereco($conexao, $id)) {
    header("Location: ../Listar/listarendentrega.php");
} else {
    header("Location: ../home.php");
}
exit;
?>
