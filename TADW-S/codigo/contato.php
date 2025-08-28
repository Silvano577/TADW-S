<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Pizzaria</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../fotosc/images.png" alt="Logo Pizzaria">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contato.php" class="ativo">Contato</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main class="contato">
        <h1>Fale Conosco</h1>
        <p>Use o formulário abaixo para enviar sua mensagem. Responderemos o mais rápido possível!</p>
        
        <form action="processa_contato.php" method="post" class="form-contato">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="mensagem">Mensagem:</label>
            <textarea name="mensagem" id="mensagem" rows="5" required></textarea>

            <button type="submit">Enviar</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Pizzaria - Todos os direitos reservados.</p>
    </footer>
</body>
</html>
