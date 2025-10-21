<?php
include "conexao.php"; // inclui o arquivo de conexão
$result = mysqli_query($conexao, "SELECT * FROM usuario"); // exemplo de consulta
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetinho PHP</title>
    <link rel="stylesheet" href="pag.css">
</head>
<body>
    <section class="php">
        <div class="coisa">
        <form class="form">
        <h3>CADASTRO</h3>
        <input type="text" placeholder="login" class="btn">
        <br>
        <input type="password" placeholder="senha" class="btn">
        <br>
        <input type="email" placeholder="email" class="btn">
        <br>
        <input type="tel" placeholder="Número de Telefone" class="btn">
        <br>
        <input  class="enviar" type="submit" onclick="logar(); return false;">
    </form>
</section>
</div>
</style>
</body>
</html>