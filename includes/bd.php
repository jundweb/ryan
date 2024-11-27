<?php
// Configuração do banco de dados
$host = 'localhost';
$user = 'root';
$senha = '';
$bdnome = 'tintas';

// Criando a conexão
$conn = new mysqli($host, $user, $senha, $bdnome);

// Verificando conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
