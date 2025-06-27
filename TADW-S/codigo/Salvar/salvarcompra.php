<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Coleta os dados do formulário
$idpedido = isset($_POST['idpedido']) ? intval($_POST['idpedido']) : 0;
$idproduto = isset($_POST['idproduto']) ? intval($_POST['idproduto']) : 0;
$quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;

if ($idpedido > 0 && $idproduto > 0 && $quantidade > 0) {
    $sucesso = salvarAtualizarItemPedido($conexao, $idpedido, $idproduto, $quantidade);

    if ($sucesso) {
        header("Location: ../Formularios/form_comprar_produto.php?idpedido=" . $idpedido);
        exit;
    } else {
        echo "Erro ao adicionar produto ao pedido.";
    }
} else {
    echo "Dados inválidos.";
}
