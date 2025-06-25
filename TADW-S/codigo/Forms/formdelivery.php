<?php
    if (isset($_GET['id'])) {
        // Editar delivery existente
        require_once "../conexao.php";
        require_once "../funcao.php";

        $id = $_GET['id'];

        $delivery = buscar_delivery($conexao, $id, "");
        if (!empty($delivery)) {
            $delivery = $delivery[0];
            $endereco_entrega = $delivery['endereco_entrega'];
            $tempo_entrega = $delivery['tempo_entrega'];
        }

        $botao = "Atualizar";
    } else {
        // Novo delivery
        $id = 0;
        $endereco_entrega = "";
        $tempo_entrega = "";

        $botao = "Cadastrar";
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $botao; ?> Delivery</title>
</head>
<body>
    <h2><?php echo $botao; ?> Delivery</h2>

    <form action="../Salvar/salvardelivery.php?id=<?php echo $id; ?>" method="post">
        <label>Endereço de Entrega:</label><br>
        <input type="text" name="endereco_entrega" value="<?php echo $endereco_entrega; ?>" required><br><br>

        <label>Tempo para Entrega:</label><br>
        <input type="time" name="tempo_entrega" value="<?php echo $tempo_entrega; ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
