<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id         = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;

$nome      = $_POST['nome'] ?? '';
$data_ani  = $_POST['data_ani'] ?? '';
$telefone  = $_POST['telefone'] ?? '';
$foto      = ""; // será preenchido pelo upload

// Diretórios para a foto
$pasta_fotos = "/var/www/html/fotos/";
$caminho_relativo = "fotos/";
$caminho_foto_final = "";

// Verificação e envio da imagem
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $nome_arquivo = $_FILES['foto']['name'];
    $caminho_temporario = $_FILES['foto']['tmp_name'];
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extensao, $permitidas)) {
        if (!is_dir($pasta_fotos)) {
            mkdir($pasta_fotos, 0755, true);
        }

        $novo_nome = uniqid() . "." . $extensao;
        $caminho_destino = $pasta_fotos . $novo_nome;
        $caminho_foto_final = $caminho_relativo . $novo_nome;

        if (!move_uploaded_file($caminho_temporario, $caminho_destino)) {
            echo "Erro: Falha ao mover a imagem.";
            exit;
        }
    } else {
        echo "Erro: Tipo de imagem não permitido.";
        exit;
    }
} elseif ($id > 0) {
    // Mantém a foto atual se não veio uma nova
    $cliente = buscar_cliente($conexao, $id, '');
    if (!empty($cliente)) {
        $caminho_foto_final = $cliente[0]['foto'];
    }
}

if ($id > 0) {
    // Atualiza cliente existente
    atualizar_cliente($conexao, $id, $nome, $data_ani, $telefone, $caminho_foto_final);
    $idcliente = $id;
} else {
    // Cria cliente novo
    $idcliente = criar_cliente($conexao, $nome, $data_ani, $telefone, $caminho_foto_final, $usuario_id);
}

// Redireciona para cadastro de endereço
if ($idcliente > 0) {
    header("Location: ../Forms/formendentrega.php?cliente_id={$idcliente}");
    exit;
} else {
    echo "Erro ao cadastrar cliente!";
}
