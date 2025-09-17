<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

// Recebe o id do usuário via query string
$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

// Inicializa variáveis
$id = 0;
$nome = $data_ani = $telefone = $foto = "";
$botao = "Cadastrar";

// Se usuário já tiver cliente vinculado, carrega dados do cliente
if ($usuario_id > 0) {
    $cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
    if (!empty($cliente)) {
        $cliente = $cliente[0];
        $id = $cliente['idcliente'];
        $nome = $cliente['nome'];
        $data_ani = $cliente['data_ani'];
        $telefone = $cliente['telefone'];
        $foto = $cliente['foto'];
        $botao = "Atualizar";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Cliente</title>
</head>
<body>
<h1><?php echo $botao; ?> Cliente</h1>

<form action="../Salvar/salvarcliente.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">

    <?php if ($id != 0 && !empty($foto)): ?>
        <p>Foto atual:</p>
        <img src="<?php echo $foto; ?>" width="100" alt="Foto do cliente"><br><br>
    <?php endif; ?>

    Foto do Cliente:<br>
    <input type="file" name="foto" accept="image/*"><br><br>

    Nome:<br>
    <input type="text" name="nome" value="<?php echo $nome; ?>" required><br><br>

    Data de Aniversário:<br>
    <input type="date" name="data_ani" value="<?php echo $data_ani; ?>" required><br><br>

    Telefone:<br>
    <input type="text" name="telefone" value="<?php echo $telefone; ?>" required><br><br>

    <input type="submit" value="<?php echo $botao; ?>">
</form>

<form action="../homeAdm.php" method="get">
    <button type="submit">Voltar</button>
</form>
</body>
</html>
