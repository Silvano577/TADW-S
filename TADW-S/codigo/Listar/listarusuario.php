<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_usuarios = listar_usuarios($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Usuários</h1>

    <?php if (count($lista_usuarios) === 0): ?>
        <p style="text-align:center;">Nenhum usuário encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_usuarios as $usuario): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($usuario['usuario'] ?? 'Sem nome') ?></h3>
                    <p>Login: <?= htmlspecialchars($usuario['email'] ?? 'Sem email') ?></p>
                    <p>Nível: <?= htmlspecialchars($usuario['tipo'] ?? 'Sem tipo') ?></p>
                    <a href="../Forms/formusuario.php?id=<?= $usuario['idusuario'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarCliente.php?id=<?= $usuario['idusuario'] ?>" onclick="return confirm('Deseja realmente excluir este usuário?');" class="btn btn-delete">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
