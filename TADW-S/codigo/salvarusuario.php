<?php

require_once "conexao.php";
require_once "funcao.php";

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$id=0;
    criar_usuario($conexao, $nome, $email, $senha_hash);

header("Location: formusuario.php");
?>