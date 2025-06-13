<?php

require_once "conexao.php";
require_once "funcao.php";

$assunto= $_POST['assunto'];
$comentario = $_POST['comentario'];





$id=0;
    registrar_feedback($conexao, $assunto, $comentario);

header("Location: formfeedback.php");
?>