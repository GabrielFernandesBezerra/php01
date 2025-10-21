<?php
include "conexao.php";

// Inserir novo pedido/recado
if(isset($_POST['cadastra'])){
    $nome  = mysqli_real_escape_string($conexao, $_POST['nome']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $msg   = mysqli_real_escape_string($conexao, $_POST['msg']);

    $sql = "INSERT INTO usuario (nome, email, mensagem) VALUES ('$nome', '$email', '$msg')";
    mysqli_query($conexao, $sql) or die("Erro ao inserir dados: " . mysqli_error($conexao));
    header("Location: mural.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8"/>
<title>Mural de pedidos</title>
<link rel="stylesheet" href="page.css"/>
<script src="scripts/jquery.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script>
$(document).ready(function() {
    $("#mural").validate({
        rules: {
            nome: { required: true, minlength: 4 },
            email: { required: true, email: true },
            msg: { required: true, minlength: 10 }
        },
        messages: {
            nome: { required: "Digite o seu nome", minlength: "O nome deve ter no mínimo 4 caracteres" },
            email: { required: "Digite o seu e-mail", email: "Digite um e-mail válido" },
            msg: { required: "Digite sua mensagem", minlength: "A mensagem deve ter no mínimo 10 caracteres" }
        }
    });
});
</script>
</head>
<body>
<div id="main">
<div id="geral">
<div id="header">
 
</div>

<section class="php">
  <div class="coisa">
<form class="form" method="post">
    <h1>Mural de pedidos</h1>
    <label>Nome:</label>
    <input class="btn" type="text" name="nome"/><br/>
    <label>Email:</label>
    <input class="btn" type="text" name="email"/><br/>
    <label>Mensagem:</label>
    <textarea class="btn" name="msg" rows="4"></textarea><br/>
    <input type="submit" value="Publicar no Mural" name="cadastra" class="enviar"/>

<?php
$seleciona = mysqli_query($conexao, "SELECT * FROM usuario ORDER BY id DESC");
while($res = mysqli_fetch_assoc($seleciona)){
    echo '<ul class="recados">';
    echo '<li><strong>ID:</strong> ' . $res['id'] . '</li>';
    echo '<li><strong>Nome:</strong> ' . htmlspecialchars($res['nome']) . '</li>';
    echo '<li><strong>Email:</strong> ' . htmlspecialchars($res['email']) . '</li>';
    echo '<li><strong>Mensagem:</strong> ' . nl2br(htmlspecialchars($res['mensagem'])) . '</li>';
    echo '</ul>';
}
?>

<div id="footer">
</form>
</div>
</section>
</div>
</div>
</div>
</body>
</html>
