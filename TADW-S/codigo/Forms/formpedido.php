<?php
    require_once "../protege.php"; 
    require_once "../conexao.php";
    require_once "../funcao.php";

    if (isset($_GET['id'])) {
        // Editar pedido existente
        $id = intval($_GET['id']);
        $pedido = buscar_pedido($conexao, $id);

        if (!empty($pedido)) {
            $endentrega = $pedido['endentrega'];
            $cliente = $pedido['cliente'];
            $idfeedback = $pedido['idfeedback'];
            $idpagamento = $pedido['idpagamento'];
            $valortotal = $pedido['valortotal'];
        }

        $botao = "Atualizar";
    } else {
        // Novo pedido
        $id = 0;
        $endentrega = "";
        $cliente = "";
        $idfeedback = "";
        $idpagamento = "";
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
        Endereço de Entrega (ID):<br>
        <input type="number" name="endentrega" value="<?php echo htmlspecialchars($endentrega); ?>" required><br><br>

        Cliente (ID):<br>
        <input type="number" name="cliente" value="<?php echo htmlspecialchars($cliente); ?>" required><br><br>

        ID Feedback:<br>
        <input type="number" name="idfeedback" value="<?php echo htmlspecialchars($idfeedback); ?>"><br><br>

        Pagamento (ID):<br>
        <input type="number" name="idpagamento" value="<?php echo htmlspecialchars($idpagamento); ?>" required><br><br>

        Valor Total (R$):<br>
        <input type="number" step="0.01" name="valortotal" value="<?php echo htmlspecialchars($valortotal); ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../homeAdm.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
