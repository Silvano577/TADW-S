<?php

require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados do formulário
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$variedade = $_POST['variedade'];
$tamanho = $_POST['tamanho'];
$preco = $_POST['preco'];

// Caminho físico onde a foto será salva
$pasta_fotos = "/var/www/html/fotos/";

// Caminho relativo salvo no banco (usado no HTML)
$caminho_relativo = "fotos/";

// Inicializa a variável da foto
$caminho_foto_final = "";

// Verifica se uma nova foto foi enviada
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

        // Gera um novo nome único
        $novo_nome = uniqid() . "." . $extensao;
        $caminho_destino_fisico = $pasta_fotos . $novo_nome;
        $caminho_foto_final = $caminho_relativo . $novo_nome;

        // Move o arquivo
        if (!move_uploaded_file($caminho_temporario, $caminho_destino_fisico)) {
            echo "Erro: Falha ao mover o arquivo enviado.";
            exit;
        }
    } else {
        echo "Erro: Tipo de imagem não permitido.";
        exit;
    }
} elseif ($id > 0) {
    // Se não foi enviada nova foto, mantém a foto anterior ao editar
    $pizza_atual = buscar_pizza($conexao, $id, "");
    if (!empty($pizza_atual)) {
        $caminho_foto_final = $pizza_atual[0]['foto'];
    }
}

// Agora, decide se vai criar ou atualizar
if ($id > 0) {
    atualizar_pizza($conexao, $id, $variedade, $tamanho, $preco, $caminho_foto_final);
} else {
    criar_pizza($conexao, $variedade, $tamanho, $preco, $caminho_foto_final);
}

// Redireciona de volta
header("Location: ../home.php
");
exit;

?>
