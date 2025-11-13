<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id        = isset($_POST['idcliente']) ? intval($_POST['idcliente']) : 0; 
$idusuario = isset($_POST['idusuario']) ? intval($_POST['idusuario']) : 0;
$nome      = trim($_POST['nome'] ?? '');
$data_ani  = $_POST['data_ani'] ?? '';
$telefone  = trim($_POST['telefone'] ?? '');
$foto      = "";

// Diretórios para foto
$pasta_fotos = "/var/www/html/fotos/";
$caminho_relativo = "fotos/";
$caminho_foto_final = "";

// Upload da foto (se houver)
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $nome_arquivo = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $ext = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
    $permitidas = ['jpg','jpeg','png','gif'];

    if (in_array($ext, $permitidas)) {
        if (!is_dir($pasta_fotos)) mkdir($pasta_fotos, 0755, true);
        $novo_nome = uniqid() . "." . $ext;
        $destino = $pasta_fotos . $novo_nome;
        if (!move_uploaded_file($tmp, $destino)) {
            die("Erro ao mover a imagem.");
        }
        $caminho_foto_final = $caminho_relativo . $novo_nome;
    } else {
        die("Tipo de imagem não permitido.");
    }
} elseif ($id > 0) {
    // Mantém a foto antiga caso não envie nova
    $cliente = buscar_cliente_por_id($conexao, $id);
    if (!empty($cliente)) {
        $caminho_foto_final = $cliente['foto'];
    }
}

// Atualiza cliente existente
if ($id > 0) {
    $sql = "UPDATE cliente 
            SET nome = ?, data_ani = ?, telefone = ?, foto = ?
            WHERE idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "ssssi", $nome, $data_ani, $telefone, $caminho_foto_final, $id);
    mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);

    // Redireciona para próxima etapa
    header("Location: ../perfil.php?cliente_id={$id}");
    exit;
}

// Caso não tenha ID, cria novo cliente
else {
    $idcliente = criar_cliente($conexao, $nome, $data_ani, $telefone, $caminho_foto_final, $idusuario);

    if ($idcliente > 0) {
        header("Location: ../perfil.php?cliente_id={$idcliente}");
        exit;
    } else {
        echo "Erro ao cadastrar cliente!";
    }
}
?>
