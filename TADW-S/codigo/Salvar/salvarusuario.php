<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome  = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($id > 0) {
    // Atualiza usuário existente
    atualizar_usuario($conexao, $id, $nome, $email, $senha);
    $idusuario = $id;
    // Redireciona para edição/criação de cliente vinculado
    header("Location: ../Forms/formcliente.php?usuario_id={$idusuario}");
} else {
    // Cria novo usuário
    $idusuario = criar_usuario($conexao, $nome, $email, $senha);

    if ($idusuario > 0) {
        // Redireciona para criar cliente vinculado
        header("Location: ../Forms/formcliente.php?usuario_id={$idusuario}");
    } else {
        echo "Erro ao cadastrar usuário!";
    }
}
exit;
