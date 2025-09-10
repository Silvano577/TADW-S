<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// Apenas apagar o registro do banco
if (deletar_usuario($conexao, $id)) {
    header("Location: ../Listar/listarusuario.php");
} else {
    header("Location: ../homeAdm.php");
}
exit;
?>
