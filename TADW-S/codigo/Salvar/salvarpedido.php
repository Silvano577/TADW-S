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

$idcliente = $cliente['idcliente'];
// 🔹 Coleta dados do formulário
$idcliente = $_SESSION['idcliente'];
$endentrega = $_POST['endentrega'] ?? null;
$tipo_entrega = $_POST['tipo_entrega'] ?? 'retirada';
$observacoes = $_POST['observacoes'] ?? '';

// 🔹 Valor total vem da sessão do carrinho
$valortotal = $_SESSION['total_compra'] ?? 0;

// 🔹 Define o status e data do pedido
$status_pedido = "Pendente";
$data_pedido = date("Y-m-d H:i:s");

// ⚙️ Verificação básica
if ($valortotal <= 0) {
    echo "<p>Erro: valor total inválido. Adicione produtos ao carrinho.</p>";
    exit;
}

// 🔹 Insere o pedido no banco
$sql = "INSERT INTO pedido (idcliente, idendentrega, tipo_entrega, observacoes, data_pedido, status_pedido, valortotal)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $sql);
if (!$stmt) {
    die("Erro na preparação da query: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param($stmt, "iissssd",
    $idcliente,
    $endentrega,
    $tipo_entrega,
    $observacoes,
    $data_pedido,
    $status_pedido,
    $valortotal
);

if (mysqli_stmt_execute($stmt)) {
    // 🔹 Pega o ID do pedido recém-criado
    $idpedido = mysqli_insert_id($conexao);

    // 🔹 Salva o ID do pedido na sessão (para o pagamento)
    $_SESSION['idpedido'] = $idpedido;

    echo "<script>
            alert('✅ Pedido realizado com sucesso!');
            window.location.href = '../Forms/formpagamento.php';
          </script>";
} else {
    echo "<p>Erro ao salvar pedido: " . mysqli_error($conexao) . "</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>
