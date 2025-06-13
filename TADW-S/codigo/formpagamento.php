<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="salvarpagamento.php" method="post">
        Qual a Forma de Pagamento: <br>
        <input type="text" name="metodo_pagamento"> <br><br>
        Valor: <br>
        <input type="text" name="valor"> <br><br>
        status de pagamento: <br>
        <input type="text" name="status_pagamento"> <br><br>
        Data de pagamento: <br>
        <input type="DATE" name="data_pagamento"> <br><br>

        <input type="submit" value="Cadastrar">

    </form>
    </form>
    <form action="home.php" method="get">
    <button type="submit">Voltar</button>
    </form>

</body>
</html