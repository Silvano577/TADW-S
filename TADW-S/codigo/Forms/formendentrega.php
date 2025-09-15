<?php
require_once "../protege.php"; // ajuste o caminho relativo

require_once "../conexao.php";
require_once "../funcao.php";

if (isset($_GET['id'])) {
    // Editar endereço existente
    $id = intval($_GET['id']);
    $endereco = buscar_endereco($conexao, $id);

    if ($endereco) {
        $rua = $endereco['rua'] ?? "";
        $numero = $endereco['numero'] ?? "";
        $complemento = $endereco['complemento'] ?? "";
        $bairro = $endereco['bairro'] ?? "";
        $cliente = $endereco['cliente'] ?? "";
    } else {
        $rua = $numero = $complemento = $bairro = $cliente = "";
    }
    $botao = "Atualizar";
} else {
    // Novo endereço
    $id = 0;
    $rua = $numero = $complemento = $bairro = $cliente = "";
    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Endereço</title>
</head>
<body>
    <h1><?php echo $botao; ?> Endereço</h1>

    <form action="../Salvar/salvarendentrega.php?id=<?php echo $id; ?>" method="post">
        Rua:<br>
        <input type="text" name="rua" value="<?php echo htmlspecialchars($rua); ?>" required><br><br>

        Número:<br>
        <input type="text" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required><br><br>

        Complemento:<br>
        <input type="text" name="complemento" value="<?php echo htmlspecialchars($complemento); ?>"><br><br>

        Bairro:<br>
        <input type="text" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" required><br><br>

        ID Cliente:<br>
        <input type="number" name="cliente" value="<?php echo htmlspecialchars($cliente); ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../homeAdm.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
