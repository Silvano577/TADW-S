<?php
require_once "../protege.php";

if (isset($_GET['id'])) {
    // Editar pagamento existente
    require_once "../conexao.php";
    require_once "../funcao.php";

    $id = $_GET['id'];

    $pagamento = buscar_pagamento($conexao, $id);


    if ($pagamento) {
        $metodo_pagamento = $pagamento['metodo_pagamento'] ?? "";
        $valor = $pagamento['valor'] ?? "";
        $status_pagamento = $pagamento['status_pagamento'] ?? "";
        $data_pagamento = $pagamento['data_pagamento'] ?? "";
    } else {
        $metodo_pagamento = "";
        $valor = "";
        $status_pagamento = "";
        $data_pagamento = "";
    }

    $botao = "Atualizar";
} else {
    // Novo pagamento
    $id = 0;
    $metodo_pagamento = "";
    $valor = "";
    $status_pagamento = "";
    $data_pagamento = "";

    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $botao ?> Pagamento</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1><?= $botao ?> Pagamento</h1>

<form action="../Salvar/salvarpagamento.php?id=<?= $id ?>" method="post" class="form-padrao">

    <label>Forma de pagamento</label><br>
    <select name="metodo_pagamento" class="input-pesquisa" required>
        <option value="">-- selecione --</option>
        <option value="pix" <?= ($metodo_pagamento === 'pix') ? 'selected' : '' ?>>PIX</option>
        <option value="cartao debito" <?= ($metodo_pagamento === 'cartao debito') ? 'selected' : '' ?>>Cartão débito</option>
        <option value="cartao credito" <?= ($metodo_pagamento === 'cartao credito') ? 'selected' : '' ?>>Cartão crédito</option>
        <option value="dinheiro" <?= ($metodo_pagamento === 'dinheiro') ? 'selected' : '' ?>>Dinheiro</option>
    </select>
    <br><br>

    <label>Valor</label><br>
    <input type="number" step="0.01" name="valor" class="input-pesquisa" required
           value="<?= htmlspecialchars($valor) ?>">
    <br><br>

    <label>Status do pagamento</label><br>
    <input type="text" name="status_pagamento" class="input-pesquisa" required
           value="<?= htmlspecialchars($status_pagamento) ?>">
    <br><br>

    <label>Data do pagamento</label><br>
    <input type="date" name="data_pagamento" class="input-pesquisa" required
           value="<?= htmlspecialchars($data_pagamento ?: date('Y-m-d')) ?>">
    <br><br>

    <button type="submit" class="btn"><?= $botao ?></button>
    <a href="../Listar/listarpagamento.php">Voltar</a>
</form>
</body>
</html>
