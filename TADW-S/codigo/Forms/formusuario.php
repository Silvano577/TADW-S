<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario  = '';
$email = '';
$senha = '';
$botao = 'Cadastrar';

if ($id > 0) {
    $dados_usuario = buscar_usuario($conexao, $id, '');
    if (!empty($dados_usuario)) {
        $u = $dados_usuario[0];
        $usuario  = $u['usuario'];
        $email = $u['email'];
        $senha = $u['senha'];
        $botao = 'Atualizar';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $botao ?> Usuário</title>
    <link rel="stylesheet" href="../css/u.css">
    <script src="../js/usuario.js"></script>
</head>

<body>
<header>
    <div class="logo">
        <img src="../fotosc/l.png" alt="Logo Pizzaria">
    </div>
    <nav>
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="../sobre.php">Sobre</a></li>
            <li><a href="../login.php">Login</a></li>
        </ul>
    </nav>
</header>

<div class="login-container">
    <h1><?= $botao ?> Usuário</h1>

    <!-- Mensagens de retorno -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'sucesso'): ?>
            <div class="sucesso">Usuário <?= strtolower($botao) ?> com sucesso!</div>
        <?php elseif ($_GET['msg'] === 'erro'): ?>
            <div class="erro">Ocorreu um erro ao <?= strtolower($botao) ?> o usuário.</div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Formulário -->
    <form action="../Salvar/salvarusuario.php?id=<?= $id ?>" method="post" onsubmit="return validarSenha();">
        <label>Nome de Usuário:</label>
        <input type="text" name="usuario" value="<?= htmlspecialchars($usuario) ?>" required>

        <label>E-mail:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label>Senha:</label>
        <input type="password" id="senha" name="senha" 
               <?= ($id == 0) ? 'required' : '' ?> 
               placeholder="<?= ($id > 0) ? 'Digite apenas se quiser alterar' : '' ?>">

        <label>Confirmar Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" 
               <?= ($id == 0) ? 'required' : '' ?> 
               placeholder="<?= ($id > 0) ? 'Confirme apenas se for alterar' : 'Repita a senha' ?>">

        <input type="submit" value="<?= $botao ?>">
    </form>
</div>
</body>
</html>
