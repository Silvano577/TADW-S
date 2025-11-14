<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Buscar o cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);

if (!$cliente) {
    echo "<p>Você ainda não possui dados de cliente.</p>";
    exit;
}

$idcliente = $cliente['idcliente'];

// Buscar pedidos do cliente logado
$sql = "
    SELECT 
        p.idpedido,
        p.valortotal,
        p.status AS status_pedido,
        DATE_FORMAT(p.data_pedido, '%d/%m/%Y %H:%i') AS data_pedido,
        pag.status_pagamento AS status_pagamento,
        pag.metodo_pagamento AS metodo_pagamento,
        d.status AS status_delivery
    FROM pedido p
    LEFT JOIN pagamento pag ON p.idpedido = pag.idpedido
    LEFT JOIN delivery d ON p.idpedido = d.pedido_id
    WHERE p.idcliente = ?
    ORDER BY p.idpedido DESC
";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="./css/m.css">
</head>
<body>
    <h1>Meus Pedidos</h1>

    <?php if (empty($pedidos)): ?>
        <p>Você ainda não fez nenhum pedido.</p>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="pedido-card">

                <h2><?= $pedido['data_pedido'] ?></h2>
                <p>Status do Pedido: <?= ucfirst($pedido['status_pedido']) ?></p>
                <p>Status Pagamento: <?= ucfirst($pedido['status_pagamento'] ?? '-') ?></p>
                <p>Método de Pagamento: <?= ucfirst($pedido['metodo_pagamento'] ?? '-') ?></p>
                <p>Status Entrega: <?= ucfirst($pedido['status_delivery'] ?? '-') ?></p>
                <p>Valor Total: R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></p>

                <!-- Itens -->
                <h3>Itens Comprados:</h3>
                <ul>
                    <?php
                    $sql_itens = "
                        SELECT pp.quantidade, pp.preco_unit, p.nome
                        FROM pedido_produto pp
                        JOIN produto p ON pp.idproduto = p.idproduto
                        WHERE pp.idpedido = ?
                    ";
                    $stmt_itens = mysqli_prepare($conexao, $sql_itens);
                    mysqli_stmt_bind_param($stmt_itens, "i", $pedido['idpedido']);
                    mysqli_stmt_execute($stmt_itens);
                    $res_itens = mysqli_stmt_get_result($stmt_itens);
                    while ($item = mysqli_fetch_assoc($res_itens)):
                    ?>
                        <li>
                            <?= htmlspecialchars($item['nome']) ?> - <?= $item['quantidade'] ?>x -
                            R$ <?= number_format($item['preco_unit'], 2, ',', '.') ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <hr>

                <!-- ===============================
                     Feedbacks do Pedido
                     =============================== -->
                <h3>Feedback</h3>
                <?php
                $sql_fb = "
                    SELECT idfeedback, assunto, comentario, nota
                    FROM feedback
                    WHERE idpedido = ? AND cliente_id = ?
                ";
                $stmt_fb = mysqli_prepare($conexao, $sql_fb);
                mysqli_stmt_bind_param($stmt_fb, "ii", $pedido['idpedido'], $idcliente);
                mysqli_stmt_execute($stmt_fb);
                $res_fb = mysqli_stmt_get_result($stmt_fb);
                $feedback = mysqli_fetch_assoc($res_fb);
                ?>

                <?php if ($feedback): ?>
                    <div class="feedback-box">
                        <p><strong>Assunto:</strong> <?= htmlspecialchars($feedback['assunto']) ?></p>
                        <p><strong>Comentário:</strong> <?= nl2br(htmlspecialchars($feedback['comentario'])) ?></p>
                        <p><strong>Nota:</strong> <?= $feedback['nota'] ?>/10</p>

                        <a class="btn-editar" href="./Forms/feedback.php?id=<?= $feedback['idfeedback'] ?>">Editar</a>
                        <a class="btn-excluir" href="./Deletar/excluir_feedback.php?id=<?= $feedback['idfeedback'] ?>"
                           onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </div>

                <?php else: ?>

                    <?php
                    // Mostrar botão Somente se tudo concluído
                    if (
                        $pedido['status_pedido'] === 'pronto' &&
                        $pedido['status_pagamento'] === 'concluido' &&
                        $pedido['status_delivery'] === 'entregue'
                    ):
                    ?>
                        <form action="./Forms/feedback.php" method="get">
                            <input type="hidden" name="idpedido" value="<?= $pedido['idpedido'] ?>">
                            <button class="btn-feedback">Enviar Feedback</button>
                        </form>
                    <?php else: ?>
                        <p class="status-pendente">Feedback disponível após entrega concluída.</p>
                    <?php endif; ?>

                <?php endif; ?>

                <hr>

                <!-- ===============================
                     LÓGICA DO BOTÃO "PEDIDO ENTREGUE"
                     =============================== -->
                <?php
                if ($pedido['status_delivery'] === null) {
                    echo '<p class="status-pendente">Aguardando atribuição de entrega...</p>';
                } elseif ($pedido['status_delivery'] !== 'entregue') {

                    $sql_del = "SELECT iddelivery FROM delivery WHERE pedido_id = ?";
                    $stmt_del = mysqli_prepare($conexao, $sql_del);
                    mysqli_stmt_bind_param($stmt_del, "i", $pedido['idpedido']);
                    mysqli_stmt_execute($stmt_del);
                    $res_del = mysqli_stmt_get_result($stmt_del);
                    $entrega = mysqli_fetch_assoc($res_del);

                    if (!empty($entrega['iddelivery'])):
                ?>
                        <form action="./Forms/editardelivery.php" method="get">
                            <input type="hidden" name="id" value="<?= $entrega['iddelivery'] ?>">
                            <button class="btn-entregue">Pedido Entregue</button>
                        </form>

                <?php
                    endif;

                } else {
                    echo '<p class="status-ok">✔ Pedido já entregue</p>';
                }
                ?>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <br>
    <a href="perfil.php">Voltar ao Perfil</a>
</body>
</html>
