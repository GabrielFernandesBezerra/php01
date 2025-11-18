<?php

// Configurações do banco
$host    = "localhost";   // normalmente não precisa alterar
$usuario = "root";        // substituir se seu usuário não for root
$senha   = "";            // substituir se você tiver senha no MySQL
$banco   = "gabrielbezerra_1d_db";       // substituir pelo nome do seu banco criado no phpMyAdmin

// Conexão MySQLi
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro ao conectar: " . mysqli_connect_error());
}

// SENSITIVE CASE suportar acentos e Ç
mysqli_set_charset($conexao, "utf8");


// Substituam os valores abaixo pelas credenciais da sua própria conta do Cloudinary
$cloud_name = "dtzsyeaze";  // exemplo: "meucloud123"
$api_key    = "844152766979421";     // exemplo: "123456789012345"
$api_secret = "sYGM28tyZKDCyApAqU9ABtkGoe8";  // exemplo: "abcdeFGHijkLMNopqrstu"

?>
