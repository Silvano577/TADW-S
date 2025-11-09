<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Recebe o ID do endereço via GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    // id inválido
    header("Location: ../Listar/listarendentrega.php");
    exit;
}

// Busca o cliente vinculado ao usuário logado
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);

// Compatibiliza o retorno: se a função retornou um array numerado com registros,
// usa o primeiro; se retornou um único assoc, usa direto.
if (is_array($cliente) && isset($cliente[0]) && is_array($cliente[0]) && !isset($cliente['idcliente'])) {
    $cliente = $cliente[0];
}

// Se ainda não for array ou não tiver idcliente, aborta
if (!is_array($cliente) || empty($cliente['idcliente'])) {
    // Usuário não tem cliente vinculado
    header("Location: ../Listar/listarendentrega.php");
    exit;
}

$idcliente = (int) $cliente['idcliente'];

// Busca o endereço no banco
$endereco = buscar_endereco($conexao, $id);

if (!$endereco) {
    // Endereço não encontrado
    header("Location: ../Listar/listarendentrega.php");
    exit;
}

// Verifica se o endereço pertence ao cliente logado
if (!isset($endereco['idcliente']) || (int)$endereco['idcliente'] !== $idcliente) {
    // Tentativa de excluir endereço de outro cliente
    header("Location: ../perfil.php");
    exit;
}

// Deleta o endereço e verifica o resultado
$exec = deletar_endereco($conexao, $id);

if ($exec) {
    // Sucesso
    header("Location: ../Listar/listarendentrega.php?deleted=1");
    exit;
} else {
    // Falha na execução: capturar erro do MySQL para debugging
    $erro = mysqli_error($conexao);
    // opcional: logue o erro em arquivo ou mostre de forma controlada
    // error_log("Erro ao deletar endereco id={$id}: {$erro}");
    // Redireciona com flag de erro (você pode exibir uma mensagem na página de listagem)
    header("Location: ../Listar/listarendentrega.php?deleted=0&erro=" . urlencode($erro));
    exit;
}
?>
