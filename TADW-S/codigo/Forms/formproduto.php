<?php
require_once "../protege.php";
protegeTipo('adm'); // só ADM pode acessar

require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se está editando um produto existente
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $produto = buscar_produto($conexao, $id, "");

    if (!empty($produto)) {
        $produto = $produto[0];
        $nome = $produto['nome'];
        $tipo = $produto['tipo'];
        $tamanho = $produto['tamanho'];
        $preco = $produto['preco'];
        $foto = $produto['foto'];
        $botao = "Atualizar";
    } else {
        $id = 0;
        $nome = $tipo = $tamanho = $preco = $foto = "";
        $botao = "Cadastrar";
    }
} else {
    $id = 0;
    $nome = $tipo = $tamanho = $preco = $foto = "";
    $botao = "Cadastrar";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Produto</title>
    <script src="../js/produto.js" defer></script>
</head>
<body>
<h1><?php echo $botao; ?> Produto</h1>

<form action="../Salvar/salvarproduto.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
    <?php if ($id != 0 && !empty($foto)): ?>
        <p>Foto atual:</p>
        <img src="<?php echo $foto; ?>" width="100" alt="Foto do produto"><br><br>
    <?php endif; ?>

    <label>Foto:</label><br>
    <input type="file" name="foto" accept="image/*"><br><br>

    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br><br>

    <label>Preço:</label><br>
    <input type="number" step="0.01" name="preco" value="<?php echo $preco; ?>" required><br><br>

    <label>Tipo:</label><br>
    <select name="tipo" id="tipo-select" required>
        <option value="">-- Selecione --</option>
        <option value="pizza" <?php if ($tipo === 'pizza') echo 'selected'; ?>>Pizza</option>
        <option value="bebida" <?php if ($tipo === 'bebida') echo 'selected'; ?>>Bebida</option>
        <option value="promocao" <?php if ($tipo === 'promocao') echo 'selected'; ?>>Promoção</option>
    </select><br><br>

    <div id="campo-tamanho" style="display: none;">
        <label>Tamanho:</label><br>
        <input type="text" name="tamanho" id="input-tamanho" value="<?php echo htmlspecialchars($tamanho); ?>"><br><br>
    </div>

    <input type="submit" value="<?php echo $botao; ?>">
</form>

<form action="../homeAdm.php" method="get">
    <button type="submit">Voltar</button>
</form>
</body>
</html>
