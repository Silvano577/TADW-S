<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Apenas ADM pode acessar
if (empty($_SESSION['tipo']) || $_SESSION['tipo'] !== "adm") {
    die("Acesso negado!");
}

// Buscar todos os pedidos
$sql = "
    SELECT 
        p.idpedido,
        p.valortotal,
        p.status AS status_pedido,
        DATE_FORMAT(p.data_pedido, '%d/%m/%Y %H:%i') AS data_pedido,
        c.nome AS cliente_nome,
        c.idcliente,

        pag.status_pagamento AS status_pagamento,
        pag.metodo_pagamento AS metodo_pagamento,

        d.status AS status_delivery
    FROM pedido p
    JOIN cliente c ON p.idcliente = c.idcliente
    LEFT JOIN pagamento pag ON p.idpedido = pag.idpedido
    LEFT JOIN delivery d ON p.idpedido = d.pedido_id
    ORDER BY p.idpedido DESC
";

$res = mysqli_query($conexao, $sql);
$pedidos = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - ADM</title>
    <link rel="stylesheet" href="../css/listarpedido.css">
</head>

<body>

<h1>Gerenciamento de Pedidos</h1>

<?php if (empty($pedidos)): ?>
    <p>Nenhum pedido encontrado.</p>
<?php else: ?>

    <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido-card">

            <h2>Pedido #<?= $pedido['idpedido'] ?> — <?= $pedido['data_pedido'] ?></h2>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente_nome']) ?></p>
            <p><strong>Status do Pedido:</strong> <?= ucfirst($pedido['status_pedido']) ?></p>
            <p><strong>Status Pagamento:</strong> <?= ucfirst($pedido['status_pagamento'] ?? '-') ?></p>
            <p><strong>Método de Pagamento:</strong> <?= ucfirst($pedido['metodo_pagamento'] ?? '-') ?></p>
            <p><strong>Status da Entrega:</strong> <?= ucfirst($pedido['status_delivery'] ?? 'pendente') ?></p>
            <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></p>

            <!-- ===============================
                 ITENS DO PEDIDO
                 =============================== -->
            <h3>Itens Comprados:</h3>
            <ul>
                <?php
                $sql_itens = "
                    SELECT pp.quantidade, pp.preco_unit, pr.nome
                    FROM pedido_produto pp
                    JOIN produto pr ON pr.idproduto = pp.idproduto
                    WHERE pp.idpedido = ?
                ";
                $stmt_itens = mysqli_prepare($conexao, $sql_itens);
                mysqli_stmt_bind_param($stmt_itens, "i", $pedido['idpedido']);
                mysqli_stmt_execute($stmt_itens);
                $result_itens = mysqli_stmt_get_result($stmt_itens);

                while ($item = mysqli_fetch_assoc($result_itens)):
                ?>
                    <li>
                        <?= htmlspecialchars($item['nome']) ?>
                        — <?= $item['quantidade'] ?>x
                        — R$ <?= number_format($item['preco_unit'], 2, ',', '.') ?>
                    </li>
                <?php endwhile; ?>
            </ul>

            <hr>

            <!-- ===============================
                 FEEDBACK DO PEDIDO
                 =============================== -->
            <h3>Feedback</h3>

            <?php
            $sql_fb = "
                SELECT idfeedback, assunto, comentario, nota
                FROM feedback
                WHERE idpedido = ? AND cliente_id = ?
            ";
            $stmt_fb = mysqli_prepare($conexao, $sql_fb);
            mysqli_stmt_bind_param($stmt_fb, "ii", $pedido['idpedido'], $pedido['idcliente']);
            mysqli_stmt_execute($stmt_fb);
            $res_fb = mysqli_stmt_get_result($stmt_fb);
            $feedback = mysqli_fetch_assoc($res_fb);
            ?>

            <?php if ($feedback): ?>
                <div class="feedback-box">
                    <p><strong>Assunto:</strong> <?= htmlspecialchars($feedback['assunto']) ?></p>
                    <p><strong>Comentário:</strong> <?= nl2br(htmlspecialchars($feedback['comentario'])) ?></p>
                    <p><strong>Nota:</strong> <?= $feedback['nota'] ?>/10</p>
                </div>
            <?php else: ?>
                <p>Nenhum feedback enviado para este pedido.</p>
            <?php endif; ?>

            <hr>

            <!-- ===============================
                 AÇÕES DO ADMIN
                 =============================== -->
            <div class="acoes">


                <a class="btn-editar" href="./Forms/editarstatus.php?id=<?= $pedido['idpedido'] ?>">Editar</a>

                <a class="btn-excluir"
                   onclick="return confirm('Deseja realmente excluir este pedido?');"
                   href="../Deletar/excluirpedido.php?id=<?= $pedido['idpedido'] ?>">
                   Excluir
                </a>
            </div>

        </div>
    <?php endforeach; ?>

<?php endif; ?>

<br>
<a href="../homeAdm.php" class="btn-voltar">Voltar</a>

</body>
</html>
