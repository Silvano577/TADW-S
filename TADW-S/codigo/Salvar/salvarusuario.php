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
        atualizar_usuario($conexao, $id, $nome, $email, $senha);
    } else {
        atualizar_usuario($conexao, $id, $nome, $email, null);
    }

    // Redireciona para o login ou área administrativa
    header("Location: ../login.php");
    exit;

} else {
    // Novo usuário
    $idusuario = criar_usuario($conexao, $nome, $email, $senha);

    if ($idusuario) {
        // Cria um cliente vinculado automaticamente
        $idcliente = criar_cliente($conexao, $nome, null, ''); // null para data_ani, '' para telefone
        if ($idcliente) {
            vincular_usuario_cliente($conexao, $idusuario, $idcliente);
        }

        // Redireciona para o formcliente para completar os dados do cliente
        header("Location: ../Forms/formcliente.php?usuario_id={$idusuario}");
        exit;
    } else {
        echo "Erro ao criar usuário.";
        exit;
    }
}
?>
