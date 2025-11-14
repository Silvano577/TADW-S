<?php
require_once "../conexao.php";
require_once "../funcao.php";
session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID inválido.");
}

$feedback = buscar_feedback($conexao, $id);

if (!$feedback) {
    die("Feedback não encontrado.");
}

excluir_feedback($conexao, $id);

header("Location: ../Listar/lista_feedbacks.php?msg=Feedback excluído");
exit;
?>
