<?php

require_once "protege.php"; // ajuste o caminho relativo


require_once "../conexao.php";
require_once "../funcao.php";

// Simulação de pedido atual (pode vir de sessão ou parâmetro GET/POST)
$idpedido = isset($_GET['idpedido']) ? intval($_GET['idpedido']) : 1;

// Buscar lista de produtos disponíveis
$produtos = listar_produtos($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produtos ao Pedido</title>
    <script>
        // Função simples para calcular valor total na tela
        function calcular() {
            let total = 0;
            document.querySelectorAll("input[type='checkbox']").forEach(chk => {
                if (chk.checked) {
                    let id = chk.value;
                    let preco = parseFloat(document.getElementById("preco_" + id).textContent.replace(',', '.'));
                    let qtd = parseInt(document.getElementById("quantidade_" + id).value) || 0;
                    total += preco * qtd;
                }
            });
            document.getElementById("valor_total").value = total.toFixed(2).replace('.', ',');
        }
    </script>
</head>
<body>

<h3>Fazer Pedido  <?php echo $idpedido; ?></h3>

<form action="../Salvar/salvarvenda.php" method="post">
    <!-- ID do pedido -->
    <input type="hidden" name="idpedido" value="<?php echo $idpedido; ?>">

    Produtos: <br><br>
    <?php foreach ($produtos as $produto): ?>
        <?php 
            $id = $produto['idproduto']; 
            $nome = htmlspecialchars($produto['nome']); 
            $preco = number_format($produto['preco'], 2, ',', '.'); 
        ?>
        <input type="checkbox" name="idproduto[]" value="<?php echo $id; ?>" onchange="calcular()">
        R$ <span id="preco_<?php echo $id; ?>"><?php echo $preco; ?></span> - <?php echo $nome; ?>
        <input type="number" name="quantidade[<?php echo $id; ?>]" id="quantidade_<?php echo $id; ?>" min="1" value="1" onchange="calcular()">
        <br>
    <?php endforeach; ?>

    <br>
    Valor Total: <br>
    <input type="text" id="valor_total" disabled><br><br>

    <input type="submit" value="Registrar Itens no Pedido">
</form>

<br>
<form action="../home.php" method="get">
    <button type="submit">Voltar</button>
</form>

</body>
</html>
