<?php
    if (isset($_GET['id'])) {
        // Editar pizza existente
        require_once "conexao.php";
        require_once "funcao.php";

        $id = $_GET['id'];

        $pizza = buscar_pizza($conexao, $id, "");
        if (!empty($pizza)) {
            $pizza = $pizza[0];
            $variedade = $pizza['variedade'];
            $tamanho = $pizza['tamanho'];
            $preco = $pizza['preco'];
            $quantidade = $pizza['quantidade'];
            $foto = $pizza['foto']; // caminho da imagem no banco
        }

        $botao = "Atualizar";
    } else {
        // Nova pizza
        $id = 0;
        $variedade = "";
        $tamanho = "";
        $preco = "";
        $quantidade = "";
        $foto = ""; // nenhuma imagem ainda

        $botao = "Cadastrar";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Pizza</title>
</head>
<body>
    <h1><?php echo $botao; ?> Pizza</h1>

    <form action="salvarpizza.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <?php if ($id != 0 && !empty($foto)): ?>
            <p>Foto atual:</p>
            <img src="<?php echo $foto; ?>" width="100" alt="Foto da pizza"><br><br>
        <?php endif; ?>

        Foto da pizza:<br>
        <input type="file" name="foto" accept="image/*"><br><br>

        Variedade:<br>
        <input type="text" name="variedade" value="<?php echo $variedade; ?>" required><br><br>

        Tamanho:<br>
        <input type="text" name="tamanho" value="<?php echo $tamanho; ?>" required><br><br>

        Preço:<br>
        <input type="text" name="preco" value="<?php echo $preco; ?>" required><br><br>

        Quantidade:<br>
        <input type="number" name="quantidade" value="<?php echo $quantidade; ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
