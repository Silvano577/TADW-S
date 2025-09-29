<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já estiver logado, vai para a home do cliente ou admin
if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim') {
    if (!empty($_SESSION['tipo']) && $_SESSION['tipo'] === 'adm') {
        header("Location: homeAdm.php");
    } else {
        header("Location: index.php"); // página principal do cliente
    }
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
        body { font-family: Arial, sans-serif; padding: 20px; }
        .erro { color: #b00020; margin: 10px 0; }
        form { max-width: 400px; margin: auto; }
        input[type=email], input[type=password] { width: 100%; padding: 8px; margin: 5px 0; }
        input[type=submit] { padding: 10px 20px; margin-top: 10px; }
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
                case 'bloqueado':
                    echo '<div class="erro">Conta bloqueada ou inexistente.</div>';
                    break;
            }
        }
        if (isset($_GET['sucesso'])) {
            echo '<div style="color:green;">Conta criada com sucesso! Faça login.</div>';
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
