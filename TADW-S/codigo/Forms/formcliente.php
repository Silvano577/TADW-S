<?php
require_once "../conexao.php";
require_once "../funcao.php";

$idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : 0;
$id         = isset($_GET['id']) ? intval($_GET['id']) : 0;

$nome     = '';
$data_ani = '';
$telefone = '';
$foto     = '';
$botao    = 'Cadastrar';

if ($id > 0) {
    $cliente = buscar_cliente($conexao, $id, '');
    if (!empty($cliente)) {
        $c = $cliente[0];
        $nome = $c['nome'];
        $data_ani = $c['data_ani'];
        $telefone = $c['telefone'];
        $foto = $c['foto'];
        $botao = 'Atualizar';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title><?= $botao ?> Cliente</title>
</head>
<body>
<h1><?= $botao ?> Cliente</h1>
<form action="../Salvar/salvarcliente.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
Nome:<br>
<input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required><br><br>

Data de Nascimento:<br>
<input type="date" name="data_ani" value="<?= htmlspecialchars($data_ani) ?>" required><br><br>

Telefone:<br>
<input type="text" name="telefone" value="<?= htmlspecialchars($telefone) ?>" required><br><br>

Foto:<br>
<input type="file" name="foto" accept="image/*"><br>
<?php if (!empty($foto)): ?>
    <img src="<?= htmlspecialchars($foto) ?>" width="100" alt="Foto atual"><br>
<?php endif; ?>
<br>

<input type="hidden" name="idusuario" value="<?= $idusuario ?>">

<input type="submit" value="<?= $botao ?>">
</form>
</body>
</html>
