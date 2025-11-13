<?php
session_start();
require_once "../protege.php";
protegeTipo('adm'); // só ADM pode acessar

require_once "../conexao.php";
require_once "../funcao.php";

// Validação segura do idpedido
if (!isset($_GET['idpedido']) || !is_numeric($_GET['idpedido'])) {
    die("ID do pedido não informado ou inválido.");
}

$idpedido = intval($_GET['idpedido']);

// Buscar pagamento do pedido
$sql = "SELECT idpagamento, status_pagamento, metodo_pagamento, valor 
        FROM pagamento 
        WHERE idpedido = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idpedido);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$pagamento = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$pagamento) {
    die("Não foi encontrado pagamento para o pedido #$idpedido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Pagamento - Pedido #<?= $idpedido ?></title>
    <link rel="stylesheet" href="../css/pagamento.css">
</head>
<body>
<h1>Editar Pagamento - Pedido #<?= $idpedido ?></h1>

<form method="POST" action="../Salvar/atualizarpagamento.php">
    <input type="hidden" name="idpagamento" value="<?= $pagamento['idpagamento'] ?>">

    <label>Método de Pagamento:</label>
    <select name="metodo" required>
        <option value="pix" <?= $pagamento['metodo_pagamento'] === 'pix' ? 'selected' : '' ?>>PIX</option>
        <option value="cartao_debito" <?= $pagamento['metodo_pagamento'] === 'cartao_debito' ? 'selected' : '' ?>>Cartão de Débito</option>
        <option value="cartao_credito" <?= $pagamento['metodo_pagamento'] === 'cartao_credito' ? 'selected' : '' ?>>Cartão de Crédito</option>
        <option value="dinheiro" <?= $pagamento['metodo_pagamento'] === 'dinheiro' ? 'selected' : '' ?>>Dinheiro</option>
    </select>

    <label>Status do Pagamento:</label>
    <select name="status" required>
        <option value="pendente" <?= $pagamento['status_pagamento'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="pago" <?= $pagamento['status_pagamento'] === 'pago' ? 'selected' : '' ?>>Pago</option>
        <option value="cancelado" <?= $pagamento['status_pagamento'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
    </select>

    <p>Valor: R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?></p>

    <button type="submit">Atualizar Pagamento</button>
</form>

<a href="../homeAdm.php">Voltar ao Painel</a>
</body>
</html>
