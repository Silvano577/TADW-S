<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

// Recebe o cliente_id do redirecionamento após cadastro
$idcliente = isset($_GET['idcliente']) ? intval($_GET['idcliente']) : 0;

// Verifica se está editando um endereço existente
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $endereco = buscar_endereco($conexao, $id);
    if ($endereco) {
        $rua = $endereco['rua'];
        $numero = $endereco['numero'];
        $complemento = $endereco['complemento'];
        $bairro = $endereco['bairro'];
        $idcliente = $endereco['idcliente'];
    }
    $botao = "Atualizar";
} else {
    // Novo endereço
    $id = 0;
    $rua = $numero = $complemento = $bairro = "";
    $idcliente = $idcliente; // pré-preenche com o cliente que acabou de se cadastrar
    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $botao ?> Endereço</title>
</head>
<body>
<h1><?= $botao ?> Endereço</h1>

<form action="../Salvar/salvarendentrega.php?id=<?= $id ?>" method="post">
    Rua:<br>
    <input type="text" name="rua" value="<?= htmlspecialchars($rua) ?>" required><br><br>

    Número:<br>
    <input type="text" name="numero" value="<?= htmlspecialchars($numero) ?>" required><br><br>

    Complemento:<br>
    <input type="text" name="complemento" value="<?= htmlspecialchars($complemento) ?>"><br><br>

    Bairro:<br>
    <input type="text" name="bairro" value="<?= htmlspecialchars($bairro) ?>" required><br><br>

    Cliente ID:<br>
    <input type="number" name="idcliente" value="<?= htmlspecialchars($idcliente) ?>" readonly required><br><br>

    <input type="submit" value="<?= $botao ?>">
</form>

<?php if ($cliente_id === 0): // Se não veio de um cadastro novo, mostra lista ?>
    <?php
    $enderecos = listar_enderecos($conexao);
    if (count($enderecos) > 0): ?>
        <h2>Endereços Cadastrados</h2>
        <div class="grid">
            <?php foreach ($enderecos as $endereco): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($endereco['rua']) ?>, <?= htmlspecialchars($endereco['numero']) ?></h3>
                    <p class="info"><strong>Bairro:</strong> <?= htmlspecialchars($endereco['bairro']) ?></p>
                    <p class="info"><strong>Complemento:</strong> <?= htmlspecialchars($endereco['complemento']) ?></p>
                    <p class="info"><strong>ID Cliente:</strong> <?= $endereco['idcliente'] ?></p>

                    <a href="../Forms/formendentrega.php?id=<?= $endereco['idendentrega'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarendentrega.php?id=<?= $endereco['idendentrega'] ?>" class="btn-delete" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nenhum endereço cadastrado.</p>
    <?php endif; ?>
<?php endif; ?>

<a href="../homeAdm.php" class="btn-voltar">Voltar</a>
</body>
</html>
