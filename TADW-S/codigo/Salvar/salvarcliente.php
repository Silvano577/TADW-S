<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id        = isset($_GET['id']) ? intval($_GET['id']) : 0;
$idusuario = isset($_POST['idusuario']) ? intval($_POST['idusuario']) : 0;
$nome      = $_POST['nome'] ?? '';
$data_ani  = $_POST['data_ani'] ?? '';
$telefone  = $_POST['telefone'] ?? '';
$foto      = "";

// Diretórios para a foto
$pasta_fotos = "/var/www/html/fotos/";
$caminho_relativo = "fotos/";
$caminho_foto_final = "";

// Upload de foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $nome_arquivo = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $ext = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
    $permitidas = ['jpg','jpeg','png','gif'];

    if (in_array($ext, $permitidas)) {
        if (!is_dir($pasta_fotos)) mkdir($pasta_fotos, 0755, true);
        $novo_nome = uniqid() . "." . $ext;
        $destino = $pasta_fotos . $novo_nome;
        if (!move_uploaded_file($tmp, $destino)) die("Erro ao mover a imagem.");
        $caminho_foto_final = $caminho_relativo . $novo_nome;
    } else die("Tipo de imagem não permitido.");
} elseif ($id > 0) {
    $cliente = buscar_cliente($conexao, $id, '');
    if (!empty($cliente)) $caminho_foto_final = $cliente[0]['foto'];
}

// Cria ou atualiza cliente
if ($id > 0) {
    atualizar_cliente($conexao, $id, $nome, $data_ani, $telefone, $caminho_foto_final, $idusuario);
    $idcliente = $id;
} else {
    $idcliente = criar_cliente($conexao, $nome, $data_ani, $telefone, $caminho_foto_final, $idusuario);
}

if ($idcliente > 0) {
    header("Location: formentrega.php?cliente_id={$idcliente}");
    exit;
} else {
    echo "Erro ao cadastrar cliente!";
}
