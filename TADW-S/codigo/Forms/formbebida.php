<?php
if (isset($_GET['id'])) {
    // Editar bebida existente
    require_once "../conexao.php";
    require_once "../funcao.php";

    $id = $_GET['id'];

    $bebida = buscar_bebida($conexao, $id, "");
    if (!empty($bebida)) {
        $bebida = $bebida[0];
        $marca = $bebida['marca'];
        $preco = $bebida['preco'];
        $foto = $bebida['foto'];
    }

    $botao = "Atualizar";
} else {
    // Nova bebida
    $id = 0;
    $marca = "";
    $preco = "";
    $foto = "";

    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Bebida</title>
</head>
<body>
    <h1><?php echo $botao; ?> Bebida</h1>

    <form action="../Salvar/salvarbebida.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <?php if ($id != 0 && !empty($foto)): ?>
            <p>Foto atual:</p>
            <img src="<?php echo $foto; ?>" width="100" alt="Foto da bebida"><br><br>
        <?php endif; ?>

        Foto da bebida:<br>
        <input type="file" name="foto" accept="image/*"><br><br>

        Marca:<br>
        <input type="text" name="marca" value="<?php echo $marca; ?>" required><br><br>

        Preço:<br>
        <input type="text" name="preco" value="<?php echo $preco; ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
