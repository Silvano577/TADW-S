<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Só processa se for POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../perfil.php");
    exit;
}

// Recebe o ID do endereço (0 = novo)
$id = intval($_GET['id'] ?? 0);

// Coleta e sanitiza os dados do formulário
$rua         = trim($_POST['rua'] ?? '');
$numero      = trim($_POST['numero'] ?? '');
$complemento = trim($_POST['complemento'] ?? '');
$bairro      = trim($_POST['bairro'] ?? '');
$idcliente   = intval($_POST['idcliente'] ?? 0);

// Validação mínima
if ($idcliente <= 0) {
    die("Erro: cliente não definido.");
}
if (empty($rua) || empty($numero) || empty($bairro)) {
    die("Erro: preencha os campos obrigatórios (rua, número, bairro).");
}

// Inserir ou atualizar
if ($id > 0) {
    $ok = atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $idcliente);
} else {
    $ok = registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $idcliente);
}

if (!$ok) {
    die("Erro ao salvar o endereço. Tente novamente.");
}

// Redireciona de volta ao perfil
header("Location: ../perfil.php?msg=endereco_salvo");
exit;
?>
