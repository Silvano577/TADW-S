<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario  = $_POST['usuario'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($id > 0) {
    // Atualiza usuário existente
    atualizar_usuario($conexao, $id, $usuario, $email, $senha);
    $idusuario = $id;
    // Redireciona para edição/criação do cliente vinculado
    header("Location: ../Forms/formcliente.php?idusuario={$idusuario}");
} else {
    // Cria novo usuário
    $idusuario = criar_usuario($conexao, $usuario, $email, $senha);

    if ($idusuario > 0) {
        // Redireciona para criar cliente vinculado
        header("Location: ../Forms/formcliente.php?idusuario={$idusuario}");
    } else {
        echo "Erro ao cadastrar usuário!";
    }
}
exit;
