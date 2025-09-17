<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;

$nome = $_POST['nome'];
$data_ani = $_POST['data_ani'];
$telefone = $_POST['telefone'];
$foto = ""; // você pode implementar upload de foto aqui

if ($id > 0) {
    // Atualiza cliente existente
    atualizar_cliente($conexao, $id, $nome, $data_ani, $telefone, $foto);
} else {
    // Cria cliente novo
    $idcliente = criar_cliente($conexao, $nome, $data_ani, $telefone, $foto);

    // Vincula o cliente ao usuário
    if ($usuario_id > 0 && $idcliente > 0) {
        vincular_usuario_cliente($conexao, $usuario_id, $idcliente);
    }
}

// Redireciona para o formulário de endereço
header("Location: ../Forms/formendentrega.php?cliente_id={$idcliente}");
exit;
?>
