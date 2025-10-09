<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario  = '';
$email = '';
$botao = 'Cadastrar';

if ($id > 0) {
    $dados_usuario = buscar_usuario($conexao, $id, '');
    if (!empty($dados_usuario)) {
        $u = $dados_usuario[0];
        $usuario  = $u['usuario'];
        $email = $u['email'];
        $botao = 'Atualizar';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title><?= $botao ?> Usuário</title>
</head>
<body>
<h1><?= $botao ?> Usuário</h1>
<form action="../Salvar/salvarusuario.php?id=<?= $id ?>" method="post">
Nome de Usuário:<br>
<input type="text" name="usuario" value="<?= htmlspecialchars($usuario) ?>" required><br><br>

E-mail:<br>
<input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

Senha:<br>
<input type="password" name="senha" <?= ($id == 0) ? 'required' : '' ?> placeholder="<?= ($id > 0) ? 'Digite apenas se quiser alterar' : '' ?>"><br><br>

<input type="submit" value="<?= $botao ?>">
</form>
</body>
</html>
