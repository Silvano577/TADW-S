<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome  = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica campos obrigatórios
if (empty($nome) || empty($email) || ($id == 0 && empty($senha))) {
    header("Location: ../Forms/formusuario.php?erro=campos");
    exit;
}

if ($id > 0) {
    // Atualização de usuário existente
    if (!empty($senha)) {
        atualizar_usuario($conexao, $id, $nome, $email, $senha);
    } else {
        atualizar_usuario($conexao, $id, $nome, $email, null);
    }
    header("Location: ../login.php");
    exit;

} else {
    // Criação de novo usuário
    $idusuario = criar_usuario($conexao, $nome, $email, $senha);

    if ($idusuario) {
        // Redireciona para cadastro do cliente vinculando o usuário
        header("Location: ../Forms/formcliente.php?usuario_id={$idusuario}");
        exit;
    } else {
        echo "Erro ao criar usuário.";
        exit;
    }
}
?>
