<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $mensagem = trim($_POST['mensagem']);


    if (empty($nome) || empty($email) || empty($mensagem)) {
        echo "<p>Por favor, preencha todos os campos.</p>";
        exit;
    }

    $destinatario = "s2023103102340062@gmail.com";


    $assunto = "Nova mensagem de contato do site";
    $corpo = "Nome: $nome\nEmail: $email\nMensagem:\n$mensagem";

    $headers = "From: $email\r\nReply-To: $email\r\n";


    if (mail($destinatario, $assunto, $corpo, $headers)) {
        echo "<p>Mensagem enviada com sucesso! Em breve entraremos em contato.</p>";
    } else {
        echo "<p>Erro ao enviar a mensagem. Tente novamente mais tarde.</p>";
    }
} else {
    echo "<p>Método inválido.</p>";
}
?>
