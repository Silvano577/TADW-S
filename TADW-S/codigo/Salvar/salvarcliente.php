<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Recebe os dados do POST
$id        = isset($_GET['id']) ? intval($_GET['id']) : 0;
$idusuario = isset($_POST['idusuario']) ? intval($_POST['idusuario']) : 0;
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
            die("Erro: Falha ao mover a imagem.");
        }
    } else {
        die("Erro: Tipo de imagem não permitido.");
    }
} elseif ($id > 0) {
    // Mantém a foto atual se não veio uma nova
    $idcliente = buscar_cliente($conexao, $id, '');
    if (!empty($idcliente)) {
        $caminho_foto_final = $idcliente[0]['foto'];
    }
}

// Verifica se idusuario é válido
if ($idusuario > 0) {
    $sql_check = "SELECT idusuario FROM usuario WHERE idusuario = ?";
    $stmt_check = mysqli_prepare($conexao, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $idusuario);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        die("Erro: Usuário informado não existe!");
    }
    mysqli_stmt_close($stmt_check);
} else {
    // Cria um usuário temporário se não houver idusuario
    $login = "user_" . uniqid();
    $email = $login . "@example.com"; // e-mail temporário
    $senha = "123456"; // senha padrão
    $idusuario = criar_usuario($conexao, $login, $email, $senha);
    if (!$idusuario) {
        die("Erro: Não foi possível criar o usuário temporário.");
    }
}

// Cria ou atualiza o cliente
if ($id > 0) {
    atualizar_cliente($conexao, $id, $nome, $data_ani, $telefone, $caminho_foto_final, $idusuario);
    $idcliente = $id;
} else {
    $idcliente = criar_cliente($conexao, $nome, $data_ani, $telefone, $caminho_foto_final, $idusuario);
}

// Redireciona para cadastro de endereço
if ($idcliente > 0) {
    header("Location: ../Forms/formentrega.php?cliente_id={$idcliente}");
    exit;
} else {
    echo "Erro ao cadastrar cliente!";
}
