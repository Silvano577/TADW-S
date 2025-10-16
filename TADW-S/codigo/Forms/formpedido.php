<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../conexao.php";
require_once "../funcao.php";

// 🔹 Verifica se usuário está logado
$usuario_id = $_SESSION['idusuario'] ?? 0;
if (!$usuario_id) {
    header("Location: ../login.php");
    exit;
}

// 🔹 Buscar cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id); // retorna array associativo único
if (!$cliente) {
    echo "<p style='color:red;'>Erro: cliente não encontrado. Cadastre seus dados no perfil antes de finalizar o pedido.</p>";
    echo "<a href='../Forms/formcliente.php?idusuario=$usuario_id'>Cadastrar Cliente</a>";
    exit;
}



// 🔹 Buscar endereços do cliente
$sql = "SELECT idendentrega, rua, numero, bairro FROM endentrega WHERE idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// 🔹 Buscar ponto fixo (da pizzaria)
$sqlPontoFixo = "SELECT idendentrega, rua, numero, bairro FROM endentrega WHERE tipo = 'ponto_fixo' LIMIT 1";
$resPontoFixo = mysqli_query($conexao, $sqlPontoFixo);
$pontoFixo = mysqli_fetch_assoc($resPontoFixo);

// 🔹 Valor total do carrinho
$valor_total = $_SESSION['total_compra'] ?? 0;
if ($valor_total <= 0) {
    echo "<p style='color:red;'>Erro: carrinho vazio. Adicione produtos antes de fazer o pedido.</p>";
    echo "<a href='../carrinho.php'>Voltar ao Carrinho</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>

<h2>Finalizar Pedido</h2>

<form action="../Salvar/salvarpedido.php" method="POST">

    <!-- ID do cliente -->
    <input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">

    <label>Escolha a forma de entrega:</label><br><br>

    <input type="radio" name="tipo_entrega" value="cliente" checked> Entrega no meu endereço<br><br>

    <select name="endentrega_cliente" required>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <option value="<?php echo $row['idendentrega']; ?>">
                <?php echo htmlspecialchars($row['rua'] . ', ' . $row['numero'] . ' - ' . $row['bairro']); ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <input type="radio" name="tipo_entrega" value="ponto_fixo"> Retirar no ponto fixo<br>
    <?php if ($pontoFixo) { ?>
        <p><strong>Endereço:</strong> 
            <?php echo htmlspecialchars($pontoFixo['rua'] . ', ' . $pontoFixo['numero'] . ' - ' . $pontoFixo['bairro']); ?>
        </p>
        <input type="hidden" name="endentrega_ponto_fixo" value="<?php echo $pontoFixo['idendentrega']; ?>">
    <?php } else { ?>
        <p style="color:red;">Nenhum ponto fixo cadastrado no sistema.</p>
    <?php } ?>

    <br><br>

    <!-- Valor total do pedido -->
    <label>Valor total do pedido:</label><br>
    <input type="number" name="valortotal" step="0.01" value="<?php echo htmlspecialchars($valor_total); ?>" readonly>
    <br><br>

    <input type="submit" value="Confirmar Pedido" class="btn">

</form>

</body>
</html>
