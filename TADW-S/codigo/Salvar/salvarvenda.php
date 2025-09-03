<?php
require_once "../conexao.php";
require_once "../funcao.php";

$idpedido = isset($_POST['idpedido']) ? intval($_POST['idpedido']) : 0;
$idprodutos = $_POST['idproduto'] ?? []; // Array de produtos
$quantidades = $_POST['quantidade'] ?? []; // Quantidades associadas

if ($idpedido > 0 && !empty($idprodutos)) {
    $sucesso_total = true;

    foreach ($idprodutos as $idproduto) {
        $idproduto = intval($idproduto);
        $qtd = isset($quantidades[$idproduto]) ? intval($quantidades[$idproduto]) : 1;

        if ($idproduto > 0 && $qtd > 0) {
            $sucesso = salvar_venda($conexao, $idpedido, $idproduto, $qtd);
            if (!$sucesso) {
                $sucesso_total = false;
            }
        }
    }

    if ($sucesso_total) {
        header("Location: ../Formularios/form_comprar_produto.php?idpedido=" . $idpedido);
        exit;
    } else {
        echo "Erro ao adicionar um ou mais produtos ao pedido.";
    }
} else {
    echo "Dados inválidos.";
}
?>
