<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="salvarpromocao.php" method="post" enctype="multipart/form-data">
        Foto do Cliente: <br>
        <input type="file" name="foto" accept="image/*" required> <br><br>
        Descriçao: <br>
        <input type="text" name="descricao"> <br><br>
        Preço: <br>
        <input type="text" name="preco"> <br><br>


        <input type="submit" value="Cadastrar">

    </form>
    </form>
    <form action="home.php" method="get">
    <button type="submit">Voltar</button>
    </form>
</body>
</html