<?php
include '../conexao.php';
session_start();

if (!isset($_SESSION['idcliente'])) {
    echo "Você precisa estar logado para fazer um pedido.";
    exit;
}

$idcliente = $_SESSION['idcliente'];

// Buscar endereços do cliente
$sql = "SELECT idendentrega, rua, numero, bairro FROM endentrega WHERE cliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Buscar endereço ponto fixo
$sqlPontoFixo = "SELECT idendentrega, rua, numero, bairro FROM endentrega WHERE tipo = 'ponto_fixo' LIMIT 1";
$resPontoFixo = mysqli_query($conexao, $sqlPontoFixo);
$pontoFixo = mysqli_fetch_assoc($resPontoFixo);
?>

<h2>Finalizar Pedido</h2>

<form action="salvarpedido.php" method="POST">

    <label>Escolha a forma de entrega:</label><br><br>

    <input type="radio" name="tipo_entrega" value="cliente" checked> Entrega no meu endereço<br><br>

    <select name="endentrega_cliente">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <option value="<?php echo $row['identrega']; ?>">
                <?php echo $row['rua'] . ', ' . $row['numero'] . ' - ' . $row['bairro']; ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <input type="radio" name="tipo_entrega" value="ponto_fixo"> Retirar no ponto fixo<br>
    <?php if ($pontoFixo) { ?>
        <p><strong>Endereço:</strong> <?php echo $pontoFixo['rua'] . ', ' . $pontoFixo['numero'] . ' - ' . $pontoFixo['bairro']; ?></p>
        <input type="hidden" name="endentrega_ponto_fixo" value="<?php echo $pontoFixo['identrega']; ?>">
    <?php } else { ?>
        <p style="color:red;">Nenhum ponto fixo cadastrado no sistema.</p>
    <?php } ?>

    <br><br>
    <input type="submit" value="Confirmar Pedido">

</form>
