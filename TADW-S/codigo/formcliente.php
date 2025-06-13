<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="salvarcliente.php" method="post" enctype="multipart/form-data">  
    Foto do Cliente: <br>
    <input type="file" name="foto" accept="image/*" required> <br><br>
    Nome: <br>
    <input type="text" name="nome"> <br><br>
    Data de Aniversario: <br>
    <input type="DATE" name="data_ani"> <br><br>
    Endereço: <br>
    <input type="text" name="endereco"> <br><br>
    Telefone: <br>
    <input type="text" name="telefone"> <br><br>
       
    <input type="submit" value="Cadastrar">

    </form>
    </form>
    <form action="home.php" method="get">
    <button type="submit">Voltar</button>
    </form>
</body>
</html