<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id       = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cliente  = intval($_POST['cliente'] ?? 0);
$rua      = $_POST['rua'] ?? '';
$numero   = $_POST['numero'] ?? '';
$complemento = $_POST['complemento'] ?? null;
$bairro   = $_POST['bairro'] ?? '';

if ($id > 0) {
    // Atualiza endereço existente
    atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $cliente);
} else {
    // Cria novo endereço vinculado ao cliente
    registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $cliente);
}

// Redireciona para próxima etapa ou listagem
header("Location: ../home.php"); // ou para fluxo de pedidos, se houver
exit;
?>
