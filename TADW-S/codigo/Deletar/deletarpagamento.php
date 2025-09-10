<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Recebe o ID do pagamento
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Chama a função para deletar pagamento
    if (deletar_pagamento($conexao, $id)) {
        header("Location: ../Listar/listarpagamento.php");
        exit;
    } else {
        // Caso haja algum problema ao deletar
        header("Location: ../homeAdm.php");
        exit;
    }
} else {
    // ID inválido
    header("Location: ../Listar/listarpagamentos.php");
    exit;
}
?>
