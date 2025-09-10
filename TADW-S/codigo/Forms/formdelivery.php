<?php
require_once "../protege.php";

if (isset($_GET['id'])) {
    require_once "../conexao.php";
    require_once "../funcao.php";

    $id = $_GET['id'];
    $delivery = buscar_delivery($conexao, $id, "");
    if (!empty($delivery)) {
        $delivery = $delivery[0];
        $rua = $delivery['rua'];
        $numero = $delivery['numero'];
        $complemento = $delivery['complemento'];
        $bairro = $delivery['bairro'];
        $cidade = $delivery['cidade'];
        $cep = $delivery['cep'];
        $tempo_entrega = $delivery['tempo_entrega_min'] . " min";
        $latitude = $delivery['latitude'];
        $longitude = $delivery['longitude'];
    }
    $botao = "Atualizar";
} else {
    $id = 0;
    $rua = $numero = $complemento = $bairro = $cidade = $cep = $tempo_entrega = "";
    $latitude = $longitude = "";
    $botao = "Cadastrar";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $botao; ?> Delivery</title>
</head>
<body>
<h2><?php echo $botao; ?> Delivery</h2>

<form action="../Salvar/salvardelivery.php?id=<?php echo $id; ?>" method="post" id="deliveryForm">

    <label>Rua:</label><br>
    <input type="text" name="rua" id="rua" value="<?php echo $rua; ?>" placeholder="Rua" required><br><br>

    <label>Número:</label><br>
    <input type="text" name="numero" id="numero" value="<?php echo $numero; ?>" placeholder="Número" required><br><br>

    <label>Complemento:</label><br>
    <input type="text" name="complemento" id="complemento" value="<?php echo $complemento; ?>" placeholder="Apto, Bloco, etc"><br><br>

    <label>Bairro:</label><br>
    <input type="text" name="bairro" id="bairro" value="<?php echo $bairro; ?>" placeholder="Bairro" required><br><br>

    <label>Cidade:</label><br>
    <input type="text" name="cidade" id="cidade" value="<?php echo $cidade; ?>" placeholder="Cidade" required><br><br>

    <label>CEP:</label><br>
    <input type="text" name="cep" id="cep" value="<?php echo $cep; ?>" placeholder="CEP" required><br><br>

    <!-- Campo apenas de exibição -->
    <label>Tempo estimado para entrega:</label><br>
    <span id="tempo_entrega"><?php echo $tempo_entrega; ?></span><br><br>

    <!-- Campos ocultos para envio ao banco -->
    <input type="hidden" name="tempo_entrega_min" id="tempo_entrega_min" value="<?php echo !empty($delivery['tempo_entrega_min']) ? $delivery['tempo_entrega_min'] : ''; ?>">
    <input type="hidden" name="latitude" id="latitude" value="<?php echo $latitude; ?>">
    <input type="hidden" name="longitude" id="longitude" value="<?php echo $longitude; ?>">

    <input type="submit" value="<?php echo $botao; ?>">
</form>

<form action="../home.php" method="get">
    <button type="submit">Voltar</button>
</form>

<script src="https://maps.googleapis.com/maps/api/js?key=SUA_CHAVE_AQUI&libraries=places"></script>
<script src="js/delivery.js"></script>
</body>
</html>
