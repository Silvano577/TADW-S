<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Se já estiver logado, vai para a home
if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>
    <style>
        .erro { color: #b00020; margin: 10px 0; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Acesso ao Sistema</h2>

    <form action="verificarlogin.php" method="post">
        <?php
        if (isset($_GET['erro'])) {
            switch ($_GET['erro']) {
                case 'campos':
                    echo '<div class="erro">Preencha todos os campos.</div>';
                    break;
                case 'email':
                    echo '<div class="erro">Email não encontrado.</div>';
                    break;
                case 'senha':
                    echo '<div class="erro">Senha incorreta.</div>';
                    break;
            }
        }
        ?>
        Email:<br>
        <input type="email" name="email" placeholder="Digite seu email" required><br>

        Senha:<br>
        <input type="password" name="senha" placeholder="Digite sua senha" required><br>

        <input type="submit" value="Acessar"><br><br>

        <a href="Forms/formusuario.php">Criar Conta</a>
    </form>
</body>
</html>
