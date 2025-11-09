<?php
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
</head>
<body>
<h1><?= htmlspecialchars($botao) ?> Endereço</h1>

<form action="../Salvar/salvarentrega.php?id=<?= $id ?>&origem=<?= urlencode($origem) ?>" method="post">
    Rua:<br>
    <input type="text" name="rua" value="<?= htmlspecialchars($rua) ?>" required><br><br>

    Número:<br>
    <input type="text" name="numero" value="<?= htmlspecialchars($numero) ?>" required><br><br>

    Complemento:<br>
    <input type="text" name="complemento" value="<?= htmlspecialchars($complemento) ?>"><br><br>

    Bairro:<br>
    <input type="text" name="bairro" value="<?= htmlspecialchars($bairro) ?>" required><br><br>

    Cliente ID:<br>
    <input type="number" name="idcliente" value="<?= $idcliente ?>" readonly required><br><br>

    <input type="submit" value="<?= htmlspecialchars($botao) ?>">
</form>

<a href="<?= $origem === 'formpedido' ? '../Forms/formpedido.php' : '../Listar/listarendentrega.php' ?>">Voltar</a>

</body>
</html>
