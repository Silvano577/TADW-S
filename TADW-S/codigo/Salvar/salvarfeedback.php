<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$assunto = $_POST['assunto'];
$comentario = $_POST['comentario'];

// Decide se é criar ou atualizar
if ($id > 0) {
    atualizar_feedback($conexao, $id, $assunto, $comentario);
} else {
    registrar_feedback($conexao, $assunto, $comentario);
}

// Redireciona para a home
header("Location: ../homeAdm.php");
exit;

?>
