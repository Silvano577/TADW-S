<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// 1) Apagar o registro do banco
if (deletar_feedback($conexao, $id)) {
    header("Location: ../Listar/listarfeedback.php");
} else {
    header("Location: ../homeAdm.php");
}
exit;
?>
