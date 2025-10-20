<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

$usuario_id = $_SESSION['idusuario'] ?? 0;
if (!$usuario_id) header("Location: ../login.php");

$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'];

$idpedido = intval($_GET['id'] ?? 0);
if ($idpedido <= 0) die("Pedido inválido.");

// 🔹 Buscar pedido
$sql = "SELECT * FROM pedido WHERE idpedido = ? AND idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idpedido, $idcliente);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($res);
if (!$pedido) die("Pedido não encontrado ou não é seu.");

// Só permite editar pedidos pendentes
if ($pedido['status'] !== 'pendente') die("Só é possível editar pedidos pendentes.");

// 🔹 Buscar endereços do cliente
$sql = "SELECT idendentrega, rua, numero, bairro FROM endentrega WHERE idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// 🔹 Buscar ponto fixo
$sqlPontoFixo = "SELECT idendentrega, rua, numero, bairro FROM endentrega WHERE tipo = 'ponto_fixo' LIMIT 1";
$resPontoFixo = mysqli_query($conexao, $sqlPontoFixo);
$pontoFixo = mysqli_fetch_assoc($resPontoFixo);

$valor_total = $pedido['valortotal'];
$idpagamento = $pedido['idpagamento'];
$identrega_atual = $pedido['identrega'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido #<?= $pedido['idpedido'] ?></title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Editar Pedido #<?= $pedido['idpedido'] ?></h1>

<form action="../Salvar/salvarpedidoeditar.php" method="POST" id="form-pedido">

    <input type="hidden" name="idpedido" value="<?= $pedido['idpedido'] ?>">
    <input type="hidden" name="idpagamento" value="<?= $idpagamento ?>">

    <label>Valor total do pedido:</label><br>
    <input type="number" name="valortotal" step="0.01" value="<?= htmlspecialchars($valor_total) ?>" readonly><br><br>

    <h3>Escolha a forma de entrega:</h3>

    <input type="radio" name="tipo_entrega" value="cliente" id="entrega_cliente" <?= in_array($identrega_atual, array_column(mysqli_fetch_all($result, MYSQLI_ASSOC), 'idendentrega')) ? 'checked' : '' ?>>
    <label for="entrega_cliente">Entrega no meu endereço</label><br>

    <select id="select_cliente">
        <?php
        mysqli_data_seek($result, 0); // reset do resultado
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <option value="<?= $row['idendentrega'] ?>" <?= ($row['idendentrega'] == $identrega_atual ? 'selected' : '') ?>>
                <?= htmlspecialchars($row['rua'] . ', ' . $row['numero'] . ' - ' . $row['bairro']) ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <input type="radio" name="tipo_entrega" value="ponto_fixo" id="entrega_ponto" <?= ($pontoFixo && $pontoFixo['idendentrega'] == $identrega_atual ? 'checked' : '') ?>>
    <label for="entrega_ponto">Retirar no ponto fixo</label><br>

    <?php if ($pontoFixo) { ?>
        <p><strong>Endereço:</strong> <?= htmlspecialchars($pontoFixo['rua'] . ', ' . $pontoFixo['numero'] . ' - ' . $pontoFixo['bairro']); ?></p>
        <input type="hidden" id="input_ponto" value="<?= $pontoFixo['idendentrega'] ?>">
    <?php } ?>

    <br><br>
    <input type="submit" value="Atualizar Pedido" class="btn">
</form>

<script src="../js/formpedido.js"></script>
</body>
</html>
