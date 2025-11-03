<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

header('Content-Type: application/json; charset=UTF-8');

// ✅ Verifica se o usuário está logado
if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Usuário não logado"]);
    exit;
}

$usuario_id = $_SESSION['idusuario'];

// ✅ Busca o cliente vinculado a esse usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
if (!$cliente) {
    echo json_encode(["sucesso" => false, "mensagem" => "Cliente não encontrado"]);
    exit;
}

$idcliente = intval($cliente['idcliente'] ?? 0);

// ✅ Captura os dados enviados pelo JavaScript (via Fetch API)
$idproduto = intval($_POST['idproduto'] ?? 0);
$quantidade = intval($_POST['quantidade'] ?? 1);

// ✅ Valida os dados recebidos
if ($idproduto <= 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Produto inválido"]);
    exit;
}

if ($quantidade <= 0) {
    $quantidade = 1; // garante quantidade mínima
}

// ✅ Adiciona ou atualiza o produto no carrinho
try {
    adicionar_ao_carrinho($conexao, $idcliente, $idproduto, $quantidade);
    echo json_encode(["sucesso" => true, "mensagem" => "Produto adicionado ao carrinho"]);
} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao adicionar: " . $e->getMessage()]);
}
