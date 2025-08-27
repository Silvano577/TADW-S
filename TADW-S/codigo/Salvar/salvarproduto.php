<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebendo os dados do formulário
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome = $_POST['nome'];
$preco = $_POST['preco'];
$tipo = $_POST['tipo'];

// Apenas produtos tipo pizza têm tamanho
$tamanho = ($tipo === 'pizza' && isset($_POST['tamanho'])) ? $_POST['tamanho'] : null;

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
    $produto = buscar_produto($conexao, $id, "");
    if (!empty($produto)) {
        $caminho_foto_final = $produto[0]['foto'];
    }
}

// Criar ou atualizar produto
if ($id > 0) {
    atualizar_produto($conexao, $id, $nome, $tipo, $tamanho, $preco, $caminho_foto_final);
} else {
    criar_produto($conexao, $nome, $tipo, $tamanho, $preco, $caminho_foto_final);
}

// Redireciona
header("Location: ../homeAdm.php");
exit;
?>
