<?php
    if (isset($_GET['id'])) {
        // Editar pedido existente
        require_once "../conexao.php";
        require_once "../funcao.php";

        $id = $_GET['id'];

        $pedido = buscar_pedido($conexao, $id, "");
        if (!empty($pedido)) {
            $pedido = $pedido[0];
            $delivery = $pedido['delivery'];
            $cliente = $pedido['cliente'];
            $idfeedback = $pedido['idfeedback'];
            $idpagamento1 = $pedido['idpagamento1'];
        }

        $botao = "Atualizar";
    } else {
        // Novo pedido
        $id = 0;
        $delivery = "";
        $cliente = "";
        $idfeedback = "";
        $idpagamento1 = "";

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
        Delivery:<br>
        <input type="text" name="delivery" value="<?php echo $delivery; ?>" required><br><br>

        Cliente:<br>
        <input type="text" name="cliente" value="<?php echo $cliente; ?>" required><br><br>

        ID Feedback:<br>
        <input type="text" name="idfeedback" value="<?php echo $idfeedback; ?>"><br><br>

        ID Pagamento:<br>
        <input type="text" name="idpagamento1" value="<?php echo $idpagamento1; ?>"><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
