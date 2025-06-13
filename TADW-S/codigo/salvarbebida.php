<?php

require_once "conexao.php";
require_once "funcao.php";

$marca = $_POST['marca'];
$preco = $_POST['preco'];
$quantidade = $_POST['quantidade'];

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
            // Salva os dados no banco, incluindo o caminho da foto
            // Atenção: você pode querer salvar um caminho relativo no banco
            // Exemplo: 'fotos/xxxxx.png' para facilitar depois no HTML
            $foto = "fotos/" . $novo_nome;

            cadastrar_bebida($conexao, $marca, $preco, $quantidade, $foto);

            header("Location: formbebida.php");
            exit;
        }
    }
}

echo "Erro: Você deve enviar uma foto válida da pizza.";
exit;



?>