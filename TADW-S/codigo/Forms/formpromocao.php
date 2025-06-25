<?php
    if (isset($_GET['id'])) {
        // Editar promoção existente
        require_once "../conexao.php";
        require_once "../funcao.php";

        $id = $_GET['id'];

        $promocao = buscar_promocao($conexao, $id, "");
        if (!empty($promocao)) {
            $promocao = $promocao[0];
            $descricao = $promocao['descricao'];
            $preco = $promocao['preco'];
            $foto = $promocao['foto'];
        }

        $botao = "Atualizar";
    } else {
        // Nova promoção
        $id = 0;
        $descricao = "";
        $preco = "";
        $foto = "";

        $botao = "Cadastrar";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Promoção</title>
</head>
<body>
    <h1><?php echo $botao; ?> Promoção</h1>

    <form action="../Salvar/salvarpromocao.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <?php if ($id != 0 && !empty($foto)): ?>
            <p>Foto atual:</p>
            <img src="<?php echo $foto; ?>" width="100" alt="Foto da promoção"><br><br>
        <?php endif; ?>

        Foto da promoção:<br>
        <input type="file" name="foto" accept="image/*"><br><br>

        Descrição:<br>
        <input type="text" name="descricao" value="<?php echo $descricao; ?>" required><br><br>

        Preço:<br>
        <input type="text" name="preco" value="<?php echo $preco; ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
