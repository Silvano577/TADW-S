<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idpedido = intval($_POST['idpedido']);
    $idproduto = intval($_POST['idproduto']);
    $quantidade = intval($_POST['quantidade']);

    if ($idpedido > 0 && $idproduto > 0 && $quantidade > 0) {
        $sucesso = salvarAtualizarItemPedido($conexao, $idpedido, $idproduto, $quantidade);

        if ($sucesso) {
            echo "<p>Produto adicionado com sucesso ao pedido!</p>";
        } else {
            echo "<p>Erro ao adicionar produto ao pedido.</p>";
        }
    } else {
        echo "<p>Dados inválidos enviados.</p>";
    }
} else {
    echo "<p>Requisição inválida.</p>";
}
?>

<a href="formcomprarproduto.php?idpedido=<?php echo $idpedido; ?>">Voltar ao formulário</a>
