<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Gera o hash da senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Decide se é criar ou atualizar
if ($id > 0) {
    atualizar_usuario($conexao, $id, $nome, $email, $senha_hash);
} else {
    criar_usuario($conexao, $nome, $email, $senha_hash);
}

// Redireciona para a home
header("Location: ../home.php");
exit;

?>
