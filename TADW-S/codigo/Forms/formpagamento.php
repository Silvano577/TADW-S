<?php
// Inicia sessão somente se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se usuário está logado
$usuario_id = $_SESSION['idusuario'] ?? 0;
if (!$usuario_id) {
    header("Location: ../login.php");
    exit;
}

// Buscar cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id); // retorna array associativo único
if (!$cliente) {
    echo "<p style='color:red;'>Erro: cliente não encontrado. Cadastre seus dados no perfil antes de finalizar o pedido.</p>";
    echo "<a href='../Forms/formcliente.php?idusuario=$usuario_id'>Cadastrar Cliente</a>";
    exit;
}

// Valor do carrinho vindo da sessão
$valor = $_SESSION['total_compra'] ?? 0;
if ($valor <= 0) {
    echo "<p style='color:red;'>Erro: carrinho vazio. Adicione produtos antes de finalizar o pedido.</p>";
    echo "<a href='../carrinho.php'>Voltar ao Carrinho</a>";
    exit;
}

// Dados iniciais do pagamento
$botao = "Gerar Pagamento";
$data_pagamento = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($botao) ?></title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1><?= htmlspecialchars($botao) ?></h1>

<form action="../Salvar/salvarpagamento.php" method="post" class="form-padrao">
    <!-- ID do cliente -->
    <input type="hidden" name="idcliente" value="<?= $cliente['idcliente'] ?>">

    <label>Forma de pagamento</label><br>
    <select name="metodo_pagamento" class="input-pesquisa" required>
        <option value="">-- selecione --</option>
        <option value="pix">PIX</option>
        <option value="cartao debito">Cartão débito</option>
        <option value="cartao credito">Cartão crédito</option>
        <option value="dinheiro">Dinheiro</option>
    </select>
    <br><br>

    <label>Valor Total</label><br>
    <input type="number" step="0.01" name="valor" class="input-pesquisa" value="<?= htmlspecialchars($valor) ?>" readonly>
    <br><br>

    <input type="hidden" name="status_pagamento" value="pendente">
    <input type="hidden" name="data_pagamento" value="<?= $data_pagamento ?>">

    <button type="submit" class="btn">💰 <?= htmlspecialchars($botao) ?></button>
    <a href="../carrinho.php">Voltar ao Carrinho</a>
</form>
</body>
</html>
