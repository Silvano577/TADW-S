<?php
session_start();
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

// Recebe o cliente_id e origem (para redirecionar depois)
$idcliente = intval($_GET['idcliente'] ?? $_GET['cliente_id'] ?? 0);
$origem = $_GET['origem'] ?? '';

// Verifica se está editando um endereço existente
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $endereco = buscar_endereco($conexao, $id);
    if ($endereco) {
        $rua = $endereco['rua'];
        $numero = $endereco['numero'];
        $complemento = $endereco['complemento'];
        $bairro = $endereco['bairro'];
        $idcliente = $endereco['idcliente'];
    } else {
        $rua = $numero = $complemento = $bairro = "";
    }
    $botao = "Atualizar";
} else {
    $rua = $numero = $complemento = $bairro = "";
    $botao = "Cadastrar";
}

// Se não houver cliente definido, redireciona
if ($idcliente <= 0) {
    die("Erro: ID do cliente não definido. Volte ao perfil e tente novamente.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($botao) ?> Endereço</title>
    <link rel="stylesheet" href="../css/entrega.css">
</head>
<body>
<div class="form-container">
    <h1><?= htmlspecialchars($botao) ?> Endereço</h1>

    <form action="../Salvar/salvarentrega.php?id=<?= $id ?>&origem=<?= urlencode($origem) ?>" method="post">
        <label for="rua">Rua:</label>
        <input type="text" name="rua" id="rua" value="<?= htmlspecialchars($rua) ?>" required>

        <label for="numero">Número:</label>
        <input type="text" name="numero" id="numero" value="<?= htmlspecialchars($numero) ?>" required>

        <label for="complemento">Complemento:</label>
        <input type="text" name="complemento" id="complemento" value="<?= htmlspecialchars($complemento) ?>">

        <label for="bairro">Bairro:</label>
        <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($bairro) ?>" required>

        <label for="idcliente">Cliente ID:</label>
        <input type="number" name="idcliente" id="idcliente" value="<?= $idcliente ?>" readonly required>

        <div class="botoes">
            <button type="submit" class="btn-salvar"><?= htmlspecialchars($botao) ?></button>
            <a href="<?= $origem === 'formpedido' ? '../Forms/formpedido.php' : '../Listar/listarendentrega.php' ?>" class="btn-cancelar">Voltar</a>
        </div>
    </form>
</div>
</body>
</html>
