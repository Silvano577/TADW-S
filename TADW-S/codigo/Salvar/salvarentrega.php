<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$rua        = $_POST['rua'] ?? '';
$numero     = $_POST['numero'] ?? '';
$complemento= $_POST['complemento'] ?? '';
$bairro     = $_POST['bairro'] ?? '';
$idcliente = $_POST['idcliente'] ?? 0;

if ($id > 0) {
    atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $idcliente);
} else {
    registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $idcliente);
}

// Fluxo final: redireciona para login
header("Location: ../login.php");
exit;
