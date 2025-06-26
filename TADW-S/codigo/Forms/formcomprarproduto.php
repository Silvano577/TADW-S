<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Buscar lista de produtos disponíveis
$produtos = listar_produtos($conexao); // Você precisa ter essa função criada

// Simulação de pedido atual (pode vir de sessão ou parâmetro GET/POST)
$idpedido = isset($_GET['idpedido']) ? intval($_GET['idpedido']) : 1;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprar Produto</title>
</head>
<body>

<h1>Comprar Produto</h1>

<form action="../Salvar/salvarcompra.php" method="post">
    <!-- ID do pedido -->
    <input type="hidden" name="idpedido" value="<?php echo $idpedido; ?>">

    <label for="idproduto">Produto:</label><br>
    <select name="idproduto" id="idproduto" required>
        <option value="">-- Selecione um produto --</option>
        <?php foreach ($produtos as $produto): ?>
            <option value="<?php echo $produto['idproduto']; ?>">
                <?php echo $produto['nome'] . " - R$ " . number_format($produto['preco'], 2, ',', '.'); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="quantidade">Quantidade:</label><br>
    <input type="number" name="quantidade" id="quantidade" min="1" value="1" required><br><br>

    <input type="submit" value="Adicionar ao Pedido">
</form>

<br>
<form action="../home.php" method="get">
    <button type="submit">Voltar</button>
</form>

</body>
</html>
