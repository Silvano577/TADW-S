<?php
require_once "../conexao.php";
require_once "../funcao.php";

if (isset($_GET['id'])) {
    // Editar usuário existente
    $id = intval($_GET['id']);
    $usuario = buscar_usuario($conexao, $id, "");

    if (!empty($usuario)) {
        $usuario = $usuario[0];
        $nome = $usuario['usuario']; // coluna correta
        $email = $usuario['email'];
        // Não trazemos a senha por segurança
    }

    $botao = "Atualizar";
} else {
    // Novo usuário
    $id = 0;
    $nome = "";
    $email = "";
    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Usuário</title>
</head>
<body>
    <h1><?php echo $botao; ?> Usuário</h1>

    <form action="../Salvar/salvarusuario.php?id=<?php echo $id; ?>" method="post">
        Nome:<br>
        <input type="text" name="nome" value="<?php echo $nome; ?>" required><br><br>

        E-mail:<br>
        <input type="email" name="email" value="<?php echo $email; ?>" required><br><br>

        Senha:<br>
        <input type="password" name="senha" <?php echo ($id == 0) ? "required" : ""; ?> placeholder="<?php echo ($id > 0) ? 'Digite apenas se quiser alterar' : ''; ?>"><br><br>

        <input type="submit" value="<?php echo $botao; ?>">
    </form>

    <form action="../index.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
