<?php
include "conexao.php"; // Conexão + variáveis Cloudinary

// ==========================================
// INSERIR NOVO PRODUTO
// ==========================================
if(isset($_POST['cadastra'])){

    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $imagem_url = "";

    // ========================
    // UPLOAD CLOUDINARY
    // ========================
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){

        $cfile = new CURLFile($_FILES['imagem']['tmp_name'], $_FILES['imagem']['type'], $_FILES['imagem']['name']);

        $timestamp = time();
        $string_to_sign = "timestamp=$timestamp$api_secret";
        $signature = sha1($string_to_sign);

        $data = [
            'file' => $cfile,
            'timestamp' => $timestamp,
            'api_key' => $api_key,
            'signature' => $signature
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/$cloud_name/image/upload");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if($response === false){ die("Erro cURL: " . curl_error($ch)); }
        curl_close($ch);

        $result = json_decode($response, true);

        if(isset($result['secure_url'])){
            $imagem_url = $result['secure_url'];
        } else {
            die("Erro Cloudinary: " . print_r($result, true));
        }
    }

    // ========================
    // SALVAR NO BANCO
    // ========================
    if($imagem_url != ""){
        $sql = "INSERT INTO usuario (nome, descricao, preco, imagem_url)
                VALUES ('$nome', '$descricao', $preco, '$imagem_url')";
        mysqli_query($conexao, $sql) or die(mysqli_error($conexao));
    }

    header("Location: mural.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8"/>
<title>Mural de Produtos</title>
<link rel="stylesheet" href="page.css"/>
</head>

<body>

<div class="php">
<div class="coisa">

    <h1 style="color:white; margin-bottom:20px;">Mural de Produtos</h1>

    <!-- ==========================
         FORMULÁRIO 
    =========================== -->
    <form id="mural" method="post" enctype="multipart/form-data" class="form">

        <h1>Cadastrar Produto</h1>

        <label>Nome do produto:</label>
        <input type="text" name="nome" class="btn" required>

        <label>Descrição:</label>
        <textarea name="descricao" required></textarea>

        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" class="btn" required>

        <label>Imagem:</label>
        <input type="file" name="imagem" class="btn" accept="image/*" required>

        <button type="submit" name="cadastra" class="enviar">Cadastrar Produto</button>
    </form>

    <!-- ==========================
         LISTA DE PRODUTOS
    =========================== -->
    <h1 style="color:white; margin-top:30px;">Produtos Cadastrados</h1>

    <?php
    $seleciona = mysqli_query($conexao, "SELECT * FROM usuario ORDER BY id DESC");

    while($res = mysqli_fetch_assoc($seleciona)):
    ?>
        <div class="form">

            <ul class="recados">
                <li><strong>ID:</strong> <?= $res['id'] ?></li>
                <li><strong>Nome:</strong> <?= htmlspecialchars($res['nome']) ?></li>
                <li><strong>Preço:</strong> R$ <?= number_format($res['preco'], 2, ',', '.') ?></li>
                <li><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($res['descricao'])) ?></li>
            </ul>

            <div style="text-align:center; margin-top:15px;">
                <img src="<?= htmlspecialchars($res['imagem_url']) ?>"
                     alt="<?= htmlspecialchars($res['nome']) ?>"
                     style="max-width:100%; border-radius:15px;">
            </div>

        </div>
    <?php endwhile; ?>

</div>
</div>

</body>
</html>

