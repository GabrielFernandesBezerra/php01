<?php
include "conexao.php"; // conexão com MySQL + variáveis do Cloudinary

// Função para deletar imagem do Cloudinary
function deletarImagemCloudinary($public_id, $cloud_name, $api_key, $api_secret) {
    $timestamp = time();
    $string_to_sign = "public_id=$public_id&timestamp=$timestamp$api_secret";
    $signature = sha1($string_to_sign);

    $data = [
        'public_id' => $public_id,
        'timestamp' => $timestamp,
        'api_key' => $api_key,
        'signature' => $signature
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/$cloud_name/image/destroy");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Excluir produto
if(isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $res = mysqli_query($conexao, "SELECT imagem_url FROM usuario WHERE id = $id");
    $dados = mysqli_fetch_assoc($res);

    if($dados && !empty($dados['imagem_url'])) {
        $url = $dados['imagem_url'];
        $parts = explode("/", $url);
        $filename = end($parts);
        $public_id = pathinfo($filename, PATHINFO_FILENAME);
        deletarImagemCloudinary($public_id, $cloud_name, $api_key, $api_secret);
    }

    mysqli_query($conexao, "DELETE FROM usuario WHERE id = $id");
    header("Location: moderar.php");
    exit;
}

// Editar produto
if(isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);
    $preco = floatval($_POST['preco']);

    $update_sql = "UPDATE usuario SET nome='$nome', descricao='$descricao', preco=$preco WHERE id=$id";
    mysqli_query($conexao, $update_sql);
    header("Location: moderar.php");
    exit;
}

// Selecionar produtos
$editar_id = isset($_GET['editar']) ? intval($_GET['editar']) : 0;
$produtos = mysqli_query($conexao, "SELECT * FROM usuario ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="page.css"/>
<title>Moderar Produtos</title>
</head>
<body>

<div class="php">
<div class="coisa">

    <h1 style="color: #fff; margin-bottom: 20px;">Moderar Produtos</h1>

    <?php while($res = mysqli_fetch_assoc($produtos)): ?>
        <div class="form">

            <ul class="recados">
                <li><strong>ID:</strong> <?= $res['id'] ?></li>
                <li><strong>Nome:</strong> <?= htmlspecialchars($res['nome']) ?></li>
                <li><strong>Preço:</strong> R$ <?= number_format($res['preco'], 2, ',', '.') ?></li>
                <li><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($res['descricao'])) ?></li>
            </ul>

            <div style="width: 100%; text-align: center; margin: 15px 0;">
                <img src="<?= htmlspecialchars($res['imagem_url']) ?>" 
                     alt="<?= htmlspecialchars($res['nome']) ?>" 
                     style="max-width: 100%; border-radius: 15px;">
            </div>

            <?php if($editar_id == $res['id']): ?>

                <!-- FORMULÁRIO DE EDIÇÃO -->
                <form method="post" action="moderar.php">
                    <input type="hidden" name="id" value="<?= $res['id'] ?>">

                    <label>Nome:</label>
                    <input class="btn" type="text" name="nome" 
                           value="<?= htmlspecialchars($res['nome']) ?>" required>

                    <label>Descrição:</label>
                    <textarea name="descricao" required><?= htmlspecialchars($res['descricao']) ?></textarea>

                    <label>Preço (R$):</label>
                    <input class="btn" type="number" step="0.01" 
                           name="preco" value="<?= $res['preco'] ?>" required>

                    <button class="enviar" type="submit" name="editar">Salvar</button>
                    <a href="moderar.php" class="enviar" 
                       style="background:#777; text-align:center; display:block; margin-top:10px;">Cancelar</a>
                </form>
            
            <?php else: ?>

                <!-- Botões normais -->
                <a class="enviar" 
                   href="moderar.php?editar=<?= $res['id'] ?>" 
                   style="text-align:center;">Editar</a>

                <a class="enviar" 
                   href="moderar.php?excluir=<?= $res['id'] ?>" 
                   onclick="return confirm('Tem certeza que deseja excluir?')"
                   style="background:#e11d48; text-align:center; margin-top:10px;">
                   Excluir
                </a>

            <?php endif; ?>

        </div>
    <?php endwhile; ?>

</div>
</div>

</body>
</html>

