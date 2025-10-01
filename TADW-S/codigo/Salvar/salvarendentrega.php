<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$rua        = $_POST['rua'] ?? '';
$numero     = $_POST['numero'] ?? '';
$complemento= $_POST['complemento'] ?? '';
$bairro     = $_POST['bairro'] ?? '';
$cliente_id = $_POST['cliente'] ?? 0;

if ($id > 0) {
    atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $cliente_id);
} else {
    registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $cliente_id);
}

// Fluxo final: redireciona para login
header("Location: ../login.php");
exit;
