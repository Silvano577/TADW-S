<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim') {
    if (!empty($_SESSION['tipo']) && $_SESSION['tipo'] === 'adm') {
        header("Location: homeAdm.php");
    } else {
        header("Location: index.php"); 
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
    <link rel="stylesheet" href="./css/lo.css">
    <header>
    <div class="logo">
        <a href="index.php">
            <img src="./fotosc/l.png" alt="Logo da Pizzaria">
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php" >Início</a></li>
            <li><a href="sobre.php" >Sobre</a></li>
            <li><a href="login.php" class="ativo">Login</a></li>
        </ul>
    </nav>
</header>

</head>
<body>
    <div class="login-container">
        <h2>Acesso ao Sistema</h2>

        <?php
        if (isset($_GET['erro'])) {
            echo '<div class="erro">';
            switch ($_GET['erro']) {
                case 'campos':
                    echo 'Preencha todos os campos.';
                    break;
                case 'usuario':
                    echo 'Usuário ou e-mail não encontrado.';
                    break;
                case 'senha':
                    echo 'Senha incorreta.';
                    break;
            }
            echo '</div>';
        }

        if (isset($_GET['sucesso'])) {
            echo '<div class="sucesso">Conta criada com sucesso! Faça login.</div>';
        }
        ?>

        <form action="verificarlogin.php" method="post">
            <label for="usuario">Usuário ou E-mail:</label>
            <input type="text" id="usuario" name="usuario" placeholder="Digite seu nome de usuário ou e-mail" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

            <input type="submit" value="Acessar">

            <a class="criar-conta" href="Forms/formusuario.php">Criar Conta</a>
        </form>
    </div>
</body>
</html>
