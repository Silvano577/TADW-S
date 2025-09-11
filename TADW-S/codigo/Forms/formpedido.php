<?php
    require_once "../protege.php"; // ajuste o caminho relativo

    require_once "../conexao.php";
    require_once "../funcao.php";

    if (isset($_GET['id'])) {
        // Editar pedido existente
        $id = $_GET['id'];
        $pedido = buscar_pedido($conexao, $id);

        if (!empty($pedido)) {
            $endentrega = $pedido['endentrega'];
            $cliente = $pedido['cliente'];
            $idfeedback = $pedido['idfeedback'];
            $idpagamento1 = $pedido['idpagamento1'];
            $valortotal = $pedido['valortotal'];
        }

        $botao = "Atualizar";
    } else {
        // Novo pedido
        $id = 0;
        $endentrega = "";
        $cliente = "";
        $idfeedback = "";
        $idpagamento1 = "";
        $valortotal = "";

        $botao = "Cadastrar";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Pedido</title>
</head>
<body>
    <h1><?php echo $botao; ?> Pedido</h1>

    <form action="../Salvar/salvarpedido.php?id=<?php echo $id; ?>" method="post">
        endentrega:<br>
        <input type="number" name="delivery" value="<?php echo $delivery; ?>" required><br><br>

        Cliente:<br>
        <input type="number" name="cliente" value="<?php echo $cliente; ?>" required><br><br>

        ID Feedback:<br>
        <input type="number" name="idfeedback" value="<?php echo $idfeedback; ?>"><br><br>

        ID Pagamento:<br>
        <input type="number" name="idpagamento1" value="<?php echo $idpagamento1; ?>" required><br><br>

        Valor Total (R$):<br>
        <input type="number" step="0.01" name="valortotal" value="<?php echo $valortotal; ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
