<?php

require_once "conexao.php";
require_once "funcao.php";

$descricao = $_POST['descricao'];
$preco = $_POST['preco'];

// Caminho absoluto da pasta fotos
$pasta_fotos = "/var/www/html/fotos/";

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $nome_arquivo = $_FILES['foto']['name'];
    $caminho_temporario = $_FILES['foto']['tmp_name'];
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extensao, $permitidas)) {
        // Cria a pasta fotos caso não exista
        if (!is_dir($pasta_fotos)) {
            mkdir($pasta_fotos, 0755, true);
        }

        // Gera nome único para a foto
        $novo_nome = uniqid() . "." . $extensao;
        $caminho_destino = $pasta_fotos . $novo_nome;

        // Move o arquivo para a pasta fotos
        if (move_uploaded_file($caminho_temporario, $caminho_destino)) {
            // Caminho relativo da imagem para salvar no banco
            $foto = "fotos/" . $novo_nome;

            // Salva os dados no banco
            criar_promocao($conexao, $descricao, $preco, $foto);

            header("Location: formpromocao.php");
            exit;
        } else {
            echo "Erro ao mover o arquivo para a pasta.";
            exit;
        }
    } else {
        echo "Erro: formato de arquivo não permitido. Envie uma imagem JPG, JPEG, PNG ou GIF.";
        exit;
    }
} else {
    echo "Erro: Você deve enviar uma foto válida da promoção.";
    exit;
}

?>
