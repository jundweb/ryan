<?php
session_start();
include '../../includes/bd.php';

// Verifica se o gestor está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Obtém os usuários da tabela 'usuarios' (sem filtrar por tipo de usuário)
$result = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Usuários - Sistema de Tintas</title>
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
                    <a href="usuarios.php" class="list-group-item list-group-item-action active">Usuários</a>
                    <a href="tintas_doadas.php" class="list-group-item list-group-item-action">Tintas Doadas</a>
                    <a href="tintas_solicitadas.php" class="list-group-item list-group-item-action">Tintas Solicitadas</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <!-- Conteúdo principal do dashboard -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Lista de Usuários</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                            <!-- Botões de Editar e Excluir -->
                                            <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <button class="btn btn-danger btn-sm" onclick="confirmarExclusao(<?php echo $row['id_usuario']; ?>)">Excluir</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de confirmação de exclusão -->
    <script>
        function confirmarExclusao(userId) {
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                window.location.href = "excluir_usuario.php?id=" + userId;
            }
        }
    </script>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
