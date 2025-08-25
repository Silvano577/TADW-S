<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px; }
        form { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 400px; margin: auto; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 5px 0 15px; border-radius: 4px; border: 1px solid #ccc; }
        input[type="submit"] { padding: 10px 20px; border: none; border-radius: 4px; background-color: #28a745; color: white; cursor: pointer; }
        input[type="submit"]:hover { background-color: #218838; }
        .erro { color: red; margin-bottom: 15px; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
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
