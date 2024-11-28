<?php
session_start();
include '../../includes/bd.php';

// Verifica se o gestor está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Obtém o nome do gestor da sessão
$nome_gestor = $_SESSION['user_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../estilos.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <!-- Menu lateral -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Início</a>
                    <a href="usuarios.php" class="list-group-item list-group-item-action">Usuários</a>
                    <a href="tintas_doadas.php" class="list-group-item list-group-item-action">Tintas Doadas</a>
                    <a href="tintas_solicitadas.php" class="list-group-item list-group-item-action">Tintas Solicitadas</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <!-- Conteúdo principal do dashboard -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Bem-vindo, <?php echo htmlspecialchars($nome_gestor); ?>!</h4>
                    </div>
                    <div class="card-body">
                        <p>Escolha uma das opções no menu lateral para começar a gerenciar o sistema.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
