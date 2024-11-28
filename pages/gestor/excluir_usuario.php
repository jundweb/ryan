<?php
session_start();
include '../../includes/bd.php';

// Verifica se o gestor está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Verifica se o ID do usuário foi passado na URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Deleta o usuário do banco de dados
    $deleteQuery = "DELETE FROM usuarios WHERE id_usuario = $userId";
    
    if ($conn->query($deleteQuery)) {
        header('Location: usuarios.php');
        exit;
    } else {
        echo "Erro ao excluir usuário: " . $conn->error;
    }
} else {
    // Se não passar o ID, redireciona para a página de usuários
    header('Location: usuarios.php');
    exit;
}
?>
