<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Delivery</title>
</head>
<body>
    <h2>Cadastro de Delivery</h2>

    <form action="salvardelivery.php" method="post">
        <label>Endereço de Entrega:</label><br>
        <input type="text" name="endereco_entrega" required><br><br>

        <label>Tempo para Entrega:</label><br>
        <input type="time" name="tempo_entrega" required><br><br>

        <input type="submit" value="Cadastrar">
    </form>

    <form action="home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
