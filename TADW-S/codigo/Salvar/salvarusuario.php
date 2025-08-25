<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica se os campos obrigatórios foram preenchidos
if (empty($nome) || empty($email) || ($id == 0 && empty($senha))) {
    header("Location: ../Forms/formusuario.php?erro=campos");
    exit;
}

// Criação ou atualização
if ($id > 0) {
    // Atualização
    if (!empty($senha)) {
        // Atualiza também a senha
        atualizar_usuario($conexao, $id, $nome, $email, $senha);
    } else {
        // Mantém a senha antiga
        atualizar_usuario($conexao, $id, $nome, $email, null);
    }
} else {
    // Novo usuário
    criar_usuario($conexao, $nome, $email, $senha);
}

// Redireciona para a página de login
header("Location: ../index.php");
exit;
?>
