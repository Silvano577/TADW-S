<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Obtém o ID do usuário (se for atualização)
$id       = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recebe dados do formulário
$usuario  = $_POST['usuario'] ?? '';
$email    = $_POST['email'] ?? '';
$senha    = $_POST['senha'] ?? '';

// ====== SE FOR ATUALIZAÇÃO ======
if ($id > 0) {
    $resultado = atualizar_usuario($conexao, $id, $usuario, $email, $senha);

    if ($resultado) {
        // Busca cliente vinculado ao usuário
        $cliente = buscar_cliente_por_usuario($conexao, $id);

        if (!empty($cliente) && isset($cliente['idcliente'])) {
            // Cliente já existe → modo atualização
            $idcliente = intval($cliente['idcliente']);
            header("Location: ../Forms/formcliente.php?idusuario={$id}&id={$idcliente}&msg=sucesso");
        } else {
            // Cliente ainda não existe → modo cadastro
            header("Location: ../Forms/formcliente.php?idusuario={$id}&msg=novo");
        }
    } else {
        // Falha na atualização
        header("Location: ../Forms/formusuario.php?id={$id}&msg=erro");
    }
    exit;
}

// ====== SE FOR NOVO USUÁRIO ======
$idusuario = criar_usuario($conexao, $usuario, $email, $senha);

if ($idusuario > 0) {
    // Redireciona para cadastrar o cliente vinculado
    header("Location: ../Forms/formcliente.php?idusuario={$idusuario}&msg=novo");
} else {
    // Erro ao criar usuário
    header("Location: ../Forms/formusuario.php?msg=erro");
}
exit;
?>
