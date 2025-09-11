<?php
require_once "../conexao.php";
require_once "../funcao.php";

// filtro por método opcional
$metodo = isset($_GET['metodo']) ? trim($_GET['metodo']) : '';

// buscar pagamentos (com ou sem filtro)
$lista_pagamentos = listar_pagamentos($conexao, $metodo);
$resumo = resumo_pagamentos($conexao, $metodo);

// opcional: lista de métodos para o select (mesma ordem do ENUM)
$metodos_possiveis = [
    '' => 'Todos',
    'pix' => 'PIX',
    'cartao debito' => 'Cartão débito',
    'cartao credito' => 'Cartão crédito',
    'dinheiro' => 'Dinheiro'
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamentos - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Pagamentos</h1>

    <!-- Filtro por método -->
    <form method="get" class="form-pesquisa">
        <select name="metodo" class="input-pesquisa" onchange="this.form.submit()">
            <?php foreach ($metodos_possiveis as $val => $label): ?>
                <option value="<?= htmlspecialchars($val) ?>" <?= ($val === $metodo) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($label) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($metodo !== ''): ?>
            <a href="listarpagamento.php" class="link-limpar">Limpar</a>
        <?php endif; ?>
    </form>

    <!-- Resumo -->
    <div class="resumo" style="text-align:center; margin-bottom:15px;">
        <strong>Registros:</strong> <?= intval($resumo['qtd']) ?> &nbsp; | &nbsp;
        <strong>Total (R$):</strong> <?= number_format((float)$resumo['total'], 2, ',', '.') ?>
    </div>

    <?php if (empty($lista_pagamentos)): ?>
        <p style="text-align:center;">Nenhum pagamento encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_pagamentos as $pg): ?>
                <div class="card">
                    <h3>Pagamento #<?= htmlspecialchars($pg['idpagamento']) ?></h3>
                    <p><strong>Método:</strong> <?= htmlspecialchars($pg['metodo_pagamento']) ?></p>
                    <p><strong>Valor:</strong> R$ <?= number_format($pg['valor'], 2, ',', '.') ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($pg['status_pagamento']) ?></p>
                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($pg['data_pagamento'])) ?></p>

                    <a href="../Forms/formpagamento.php?id=<?= $pg['idpagamento'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarpagamentos.php?id=<?= $pg['idpagamento'] ?>" class="btn btn-delete"
                       onclick="return confirm('Deseja realmente excluir este pagamento?');">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
