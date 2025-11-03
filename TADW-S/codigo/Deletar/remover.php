<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

// Verifica se o ID do item foi enviado
if (!isset($_POST['idcarrinho']) || empty($_POST['idcarrinho'])) {
    die("ID do item não informado.");
}

$idcarrinho = intval($_POST['idcarrinho']);

// Prepara o comando para remover o item do carrinho
$sql = "DELETE FROM carrinho WHERE idcarrinho = ?";
$stmt = mysqli_prepare($conexao, $sql);

if (!$stmt) {
    die("Erro ao preparar exclusão: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param($stmt, "i", $idcarrinho);

if (mysqli_stmt_execute($stmt)) {
    // Redireciona de volta ao carrinho
    header("Location: ../carrinho.php");
    exit;
} else {
    die("Erro ao remover item: " . mysqli_stmt_error($stmt));
}
