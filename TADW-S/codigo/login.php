<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>

</head>
<body>
    <h2 style="text-align:center;">Acesso ao Sistema</h2>

    <form action="verificarlogin.php" method="post">
        <?php
        if (isset($_GET['erro'])) {
            switch ($_GET['erro']) {
                case 'campos':
                    echo '<div class="erro">Preencha todos os campos</div>';
                    break;
                case 'email':
                    echo '<div class="erro">Email não encontrado</div>';
                    break;
                case 'senha':
                    echo '<div class="erro">Senha incorreta</div>';
                    break;
            }
        }
        ?>

        Email:<br>
        <input type="text" name="email" placeholder="Digite seu email" required><br>

        Senha:<br>
        <input type="password" name="senha" placeholder="Digite sua senha" required><br>

        <input type="submit" value="Acessar"><br><br>

        <a href="Forms/formusuario.php">Criar Conta</a>
    </form>
</body>
</html>
