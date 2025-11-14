<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id'] ?? 0);
$idusuario = intval($_GET['idusuario'] ?? 0);
$cliente = null;

// Buscar dados do cliente, se for edição
if ($id > 0) {
    $cliente = buscar_cliente_por_id($conexao, $id);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $id > 0 ? 'Editar Cliente' : 'Cadastrar Cliente'; ?></title>
    <link rel="stylesheet" href="../css/clin.css">
    <script src="../js/cliente.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
    <div class="logo">
        <a href="../index.php">
            <img src="../fotosc/l.png" alt="Logo da Pizzaria">
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="../sobre.php">Sobre</a></li>
            <li><a href="../login.php">Login</a></li>
        </ul>
    </nav>
</header>


<section class="form-container">
    <h2><?= $id > 0 ? 'Atualizar Dados do Cliente' : 'Cadastro de Cliente'; ?></h2>

    <form action="../Salvar/salvarcliente.php" method="POST" enctype="multipart/form-data">
        <!-- Envia ID oculto para atualização -->
        <input type="hidden" name="idcliente" value="<?= htmlspecialchars($cliente['idcliente'] ?? '') ?>">
        <input type="hidden" name="idusuario" value="<?= htmlspecialchars($idusuario ?: ($cliente['idusuario'] ?? '')) ?>">

        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="nome" required
                   value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="data_ani">Data de Nascimento:</label>
            <input type="date" name="data_ani" id="data_ani" required
                   value="<?= htmlspecialchars($cliente['data_ani'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" required
                   value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="foto">Foto de Perfil:</label><br>
            <?php if (!empty($cliente['foto'])): ?>
                <img src="../<?= htmlspecialchars($cliente['foto']) ?>" alt="Foto atual" class="foto-preview"><br>
            <?php endif; ?>
            <input type="file" name="foto" id="foto" accept="image/*">
        </div>

        <div class="botoes">
            <button type="submit" class="btn-salvar">
                <?= $id > 0 ? 'Atualizar' : 'Salvar'; ?>
            </button>
            <a href="../perfil.php" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</section>

</body>
</html>
