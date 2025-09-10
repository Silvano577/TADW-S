<?php
require_once "../conexao.php";
require_once "../funcao.php";

$termo = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($termo !== '') {
    $lista_usuarios = buscar_usuario($conexao, 0, $termo);
} else {
    $lista_usuarios = listar_usuarios($conexao);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Usuários - Pizzaria Delícia</title>
    <?php
    // Gera uma querystring com o timestamp do arquivo para forçar o navegador a baixar a versão atual
    $cssPath = __DIR__ . '/../css/lista_padrao.css';
    $cssVer  = file_exists($cssPath) ? filemtime($cssPath) : time();
    ?>
    <link rel="stylesheet" href="../css/lista_padrao.css?v=<?= $cssVer ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Usuários</h1>

    <!-- Barra de pesquisa -->
    <form method="get" class="form-pesquisa">
        <input type="text" name="busca" placeholder="Pesquisar usuário..." 
               value="<?= htmlspecialchars($termo) ?>" class="input-pesquisa">
        <button type="submit" class="btn-pesquisa">Buscar</button>
        <?php if ($termo !== ''): ?>
            <a href="listarusuario.php" class="link-limpar">Limpar</a>
        <?php endif; ?>
    </form>

    <?php if (empty($lista_usuarios)): ?>
        <p style="text-align:center;">Nenhum usuário encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_usuarios as $usuario): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($usuario['usuario'] ?? 'Sem nome') ?></h3>
                    <p>Login: <?= htmlspecialchars($usuario['email'] ?? 'Sem email') ?></p>
                    <p>Nível: <?= htmlspecialchars($usuario['tipo'] ?? 'Sem tipo') ?></p>
                    <a href="../Forms/formusuario.php?id=<?= $usuario['idusuario'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarusuario.php?id=<?= $usuario['idusuario'] ?>" class="btn btn-delete"
                       onclick="return confirm('Deseja realmente excluir este usuário?');">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
