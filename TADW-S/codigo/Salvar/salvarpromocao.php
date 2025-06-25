<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];

// Pasta onde as fotos serão salvas
$pasta_fotos = "/var/www/html/fotos/";
$caminho_relativo = "fotos/";
$caminho_foto_final = "";

// Verifica se foi enviada uma nova foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $nome_arquivo = $_FILES['foto']['name'];
    $caminho_temporario = $_FILES['foto']['tmp_name'];
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extensao, $permitidas)) {
        // Cria a pasta se não existir
        if (!is_dir($pasta_fotos)) {
            mkdir($pasta_fotos, 0755, true);
        }

        // Gera nome único para a foto
        $novo_nome = uniqid() . "." . $extensao;
        $caminho_destino = $pasta_fotos . $novo_nome;
        $caminho_foto_final = $caminho_relativo . $novo_nome;

        // Move o arquivo
        if (!move_uploaded_file($caminho_temporario, $caminho_destino)) {
            echo "Erro: Falha ao mover a imagem.";
            exit;
        }
    } else {
        echo "Erro: Tipo de imagem não permitido.";
        exit;
    }
} elseif ($id > 0) {
    // Se não foi enviada nova foto, mantém a foto atual ao editar
    $promocao_atual = buscar_promocao($conexao, $id, "");
    if (!empty($promocao_atual)) {
        $caminho_foto_final = $promocao_atual[0]['foto'];
    }
}

// Decide se é criar ou atualizar
if ($id > 0) {
    atualizar_promocao($conexao, $id, $descricao, $preco, $caminho_foto_final);
} else {
    criar_promocao($conexao, $descricao, $preco, $caminho_foto_final);
}

// Redireciona para a home
header("Location: ../home.php");
exit;

?>
