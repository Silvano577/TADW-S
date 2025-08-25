<?php
    require_once "../protege.php"; // ajuste o caminho relativo
    if (isset($_GET['id'])) {
        // Editar feedback existente
        require_once "../conexao.php";
        require_once "../funcao.php";

        $id = $_GET['id'];

        $feedback = buscar_feedback($conexao, $id, "");
        if (!empty($feedback)) {
            $feedback = $feedback[0];
            $assunto = $feedback['assunto'];
            $comentario = $feedback['comentario'];
        }

        $botao = "Atualizar";
    } else {
        // Novo feedback
        $id = 0;
        $assunto = "";
        $comentario = "";

        $botao = "Cadastrar";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Feedback</title>
</head>
<body>
    <h1><?php echo $botao; ?> Feedback</h1>

    <form action="../Salvar/salvarfeedback.php?id=<?php echo $id; ?>" method="post">
        Assunto:<br>
        <input type="text" name="assunto" value="<?php echo $assunto; ?>" required><br><br>

        Comentário:<br>
        <input type="text" name="comentario" value="<?php echo $comentario; ?>" required><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
