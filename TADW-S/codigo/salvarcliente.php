<?php

require_once "conexao.php";
require_once "funcao.php";

$nome = $_POST['nome'];
$data_ani = $_POST['data_ani'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];

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
            // Corrigido: define o caminho relativo da imagem para salvar no banco
            $foto = "fotos/" . $novo_nome;

            // Salva os dados no banco
            criar_cliente($conexao, $nome, $data_ani, $endereco, $telefone, $foto);

            header("Location: home.php");
            exit;
        } else {
            echo "Erro ao mover o arquivo para o diretório de destino.";
            exit;
        }
    } else {
        echo "Erro: formato de arquivo não permitido. Envie uma imagem JPG, JPEG, PNG ou GIF.";
        exit;
    }
} else {
    echo "Erro: Você deve enviar uma foto válida do cliente.";
    exit;
}

?>
