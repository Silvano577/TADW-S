<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// 🔹 Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

// 🔹 Busca o cliente vinculado ao usuário logado
$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

// 🔹 Validação dos dados recebidos do formulário
if (
    empty($_POST['idpedido']) ||
    empty($_POST['metodo']) ||
    empty($_POST['valor'])
) {
    die("Dados do pagamento inválidos.");
}

$idpedido = intval($_POST['idpedido']);
$metodo = $_POST['metodo'];
$valor = floatval($_POST['valor']);

// 🔹 Insere o pagamento como PENDENTE
$sql_pag = "INSERT INTO pagamento (idcliente, idpedido, valor, metodo_pagamento, status_pagamento, data_pagamento)
            VALUES (?, ?, ?, ?, 'pendente', NOW())";
$stmt_pag = mysqli_prepare($conexao, $sql_pag);

if (!$stmt_pag) {
    die("Erro na preparação da query: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param($stmt_pag, 'iids', $idcliente, $idpedido, $valor, $metodo);

if (mysqli_stmt_execute($stmt_pag)) {
    echo "<h3>✅ Pagamento registrado como pendente!</h3>";
    echo "<p>Pedido #{$idpedido} no valor de R$ " . number_format($valor, 2, ',', '.') . "</p>";
    echo "<p>Status atual: <strong>PENDENTE</strong></p>";
    echo "<a href='../perfil.php'>Voltar ao perfil</a>";
} else {
    echo "Erro ao registrar pagamento: " . mysqli_stmt_error($stmt_pag);
}

mysqli_stmt_close($stmt_pag);
mysqli_close($conexao);
?>
