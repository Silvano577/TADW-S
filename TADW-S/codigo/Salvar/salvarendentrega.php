<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'] ?? null;
$bairro = $_POST['bairro'];
$cliente = intval($_POST['cliente']);

// Decide se é criar ou atualizar
if ($id > 0) {
    atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $cliente);
} else {
    registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $cliente);
}

// Se veio do fluxo de cliente recém-cadastrado, redireciona para o próximo passo
if (isset($_GET['from_cliente'])) {
    // Ex: para criar pedido ou continuar o fluxo
    header("Location: ../Forms/formpedido.php?cliente_id={$cliente}");
} else {
    // Redireciona para a listagem normal de endereços
    header("Location: ../Listar/listarendentrega.php");
}
exit;
?>
