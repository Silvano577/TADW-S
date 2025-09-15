<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delivery = buscar_delivery($conexao, $id);
    $pedido_id = $delivery['pedido_id'] ?? "";
    $status = $delivery['status'] ?? "atribuido";
    $botao = "Atualizar";
} else {
    $id = 0;
    $pedido_id = "";
    $status = "atribuido";
    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Delivery</title>
</head>
<body>
    <h1><?php echo $botao; ?> Delivery</h1>

    <form action="../Salvar/salvardelivery.php?id=<?php echo $id; ?>" method="post">
        Pedido ID:<br>
        <input type="number" name="pedido_id" value="<?php echo htmlspecialchars($pedido_id); ?>" required><br><br>

        Status:<br>
        <select name="status">
            <option value="atribuido" <?= $status=='atribuido'?'selected':'' ?>>Atribuído</option>
            <option value="a_caminho" <?= $status=='a_caminho'?'selected':'' ?>>A Caminho</option>
            <option value="entregue" <?= $status=='entregue'?'selected':'' ?>>Entregue</option>
            <option value="falha" <?= $status=='falha'?'selected':'' ?>>Falha</option>
        </select><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
