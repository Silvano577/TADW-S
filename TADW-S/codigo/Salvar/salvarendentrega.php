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

// Redireciona para a listagem
header("Location: ../Listar/listarendentrega.php");
exit;
?>
